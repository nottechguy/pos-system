<?php

namespace App\Jobs;

use App\Models\Sale;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Str;
use Throwable;
use Exception;

class PrintSaleReceiptJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public array $backoff = [10, 30, 60];

    public string $traceId;

    /**
     * Wall-clock timestamp recorded when the job object is created.
     * Used to estimate dispatch-to-worker-start delay.
     */
    public float $dispatchedAtUnix;

    public function __construct(
        public readonly int $saleId,
        public readonly string $mode = 'Ticket'
    ) {
        $this->traceId = (string) Str::uuid();
        $this->dispatchedAtUnix = microtime(true);
    }

    public function handle(): void
    {
        $jobStartedNs = hrtime(true);
        $attempt = $this->job?->attempts() ?? 1;

        $context = [
            'trace_id' => $this->traceId,
            'sale_id' => $this->saleId,
            'mode' => $this->mode,
            'attempt' => $attempt,
        ];

        $elapsedMs = static fn (int $start): float =>
            round((hrtime(true) - $start) / 1_000_000, 2);

        Log::info('printer.receipt_job.started', $context + [
            // Only the first attempt approximates initial queue waiting time.
            'dispatch_to_start_ms' => $attempt === 1
                ? round((microtime(true) - $this->dispatchedAtUnix) * 1000, 2)
                : null,
        ]);

        try {
            // Stage 1: Database retrieval and Eloquent relationship hydration.
            $loadStartedNs = hrtime(true);

            $sale = Sale::with([
                'store.business',
                'cashier',
                'customer',
                'items.discounts',
                'items.product.saleUnit',
                'payments.paymentMethod',
            ])->findOrFail($this->saleId);

            $loadMs = $elapsedMs($loadStartedNs);

            Log::info('printer.receipt_job.sale_loaded', $context + [
                'load_ms' => $loadMs,
                'item_count' => $sale->items->count(),
                'payment_count' => $sale->payments->count(),
            ]);

            // Stage 2: Construct the receipt payload.
            $payloadStartedNs = hrtime(true);

            $business = $sale->store->business;
            $store = $sale->store;

            $paymentMethods = $sale->payments
                ->map(fn ($payment) => __(
                    "payment_methods.{$payment->paymentMethod->code}.name"
                ))
                ->unique()
                ->implode(', ');

            $payload = [
                'business' => [
                    'name' => $business->name,
                    'legalName' => $business->legal_name,
                    'rfc' => $business->tax_id,
                    'address' => "{$store->address_line_1}, {$store->city}, {$store->state}",
                    'phone' => $store->phone_line_1 ?? $business->phone_line_1,
                    'email' => $store->email ?? $business->email,
                    'website' => $business->website,
                ],
                'order' => [
                    'mode' => $this->mode,
                    'ticketNumber' => $sale->number,
                    'date' => $sale->completed_at
                        ->timezone($store->timezone)
                        ->format('d/m/Y, h:i A'),
                    'cashierName' => $sale->cashier->first_name
                        . ' ' . $sale->cashier->last_name,
                    'customerName' => $sale->customer
                        ? $sale->customer->display_name
                        : null,
                    'paymentMethod' => $paymentMethods,

                    'items' => $sale->items->map(function ($item) {
                        $discount = $item->discounts->first();
                        $displayName = $item->name_snapshot;
                        $formattedQuantity = '';

                        if ($item->product && $item->product->allows_fractional_qty) {
                            $unitCode = $item->product->saleUnit
                                ? $item->product->saleUnit->code
                                : 'kilogram';

                            $unitSymbol = __("messages.measurement_units.{$unitCode}.symbol");

                            if ($unitSymbol === "messages.measurement_units.{$unitCode}.symbol") {
                                $unitSymbol = $unitCode;
                            }

                            $qty = (float) $item->quantity;

                            if ($qty >= 1.0) {
                                $formattedQty = floor($qty) == $qty
                                    ? (int) $qty
                                    : rtrim(rtrim(number_format($qty, 3, '.', ''), '0'), '.');

                                $formattedQuantity = "{$formattedQty} {$unitSymbol}";
                            } else {
                                $grams = round($qty * 1000);
                                $formattedQuantity = "{$grams} g";
                            }

                            $displayName .= " {$formattedQuantity}";
                        }

                        $discountAmount = 0;
                        $discountName = null;

                        if ($discount) {
                            $discountName = $discount->name
                                ?? ($discount->pivot->rule_name ?? 'Descuento Promocional');

                            $discountAmount = (float) (
                                $discount->amount
                                ?? ($discount->pivot->discount_amount ?? 0)
                            );
                        }

                        return [
                            'name' => $displayName,
                            'quantity' => (float) $item->quantity,
                            'unitPrice' => (float) $item->unit_price,
                            'discountName' => $discountName,
                            'discountAmount' => $discountAmount,
                            'allowsFractionalQty' => (bool) (
                                $item->product?->allows_fractional_qty ?? false
                            ),
                            'formattedQuantity' => $formattedQuantity,
                        ];
                    })->toArray(),

                    'discount' => (float) $sale->discount_total,
                    'amountPaid' => (float) $sale->payments->sum('received_amount'),
                    'change' => (float) ($sale->payments->first()->change_amount ?? 0),

                    'footerLines' => [
                        'Gracias por su compra',
                        'Conserve su ticket',
                        'Vuelva pronto',
                    ],
                ],
            ];

            $payloadMs = $elapsedMs($payloadStartedNs);

            Log::info('printer.receipt_job.payload_built', $context + [
                'payload_build_ms' => $payloadMs,
                'item_count' => count($payload['order']['items']),
            ]);

            // Stage 3: HTTP request to the persistent Node.js printer server.
            $httpStartedNs = hrtime(true);

            try {
                $response = Http::withToken(config('services.printer.token'))
                    ->connectTimeout(1)
                    ->timeout(5)
                    ->post(config('services.printer.url'), $payload);
            } catch (ConnectionException $e) {
                Log::error('printer.receipt_job.http_connection_failed', $context + [
                    'http_ms' => $elapsedMs($httpStartedNs),
                    'error' => $e->getMessage(),
                ]);

                throw $e;
            } catch (Throwable $e) {
                Log::error('printer.receipt_job.http_exception', $context + [
                    'http_ms' => $elapsedMs($httpStartedNs),
                    'error' => $e->getMessage(),
                ]);

                throw $e;
            }

            $httpMs = $elapsedMs($httpStartedNs);

            Log::info('printer.receipt_job.http_response', $context + [
                'http_ms' => $httpMs,
                'http_status' => $response->status(),
                'successful' => $response->successful(),
            ]);

            if ($response->failed()) {
                $status = $response->status();
                $errorMsg = $response->json('error') ?? $response->body();

                Log::error('printer.receipt_job.printer_failed', $context + [
                    'http_status' => $status,
                    'error' => $errorMsg,
                ]);

                if ($status >= 500) {
                    throw new Exception("Printer hardware error: {$errorMsg}");
                }

                return;
            }

            Log::info('printer.receipt_job.succeeded', $context);
        } finally {
            Log::info('printer.receipt_job.finished', $context + [
                'total_job_ms' => $elapsedMs($jobStartedNs),
            ]);
        }
    }
}