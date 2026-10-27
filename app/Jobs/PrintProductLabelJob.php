<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class PrintProductLabelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public array $backoff = [5, 15, 30];

    public function __construct(
        public readonly int $productId,
        public readonly int $storeId,
        public readonly float $weight,
        public readonly string $dynamicBarcode,
        public readonly int $copies = 1
    ) {}

    public function handle(): void
    {
        $product = Product::with('saleUnit')->findOrFail($this->productId);
        $store = Store::with('business')->findOrFail($this->storeId);

        // 1. Get the unit code (e.g., 'kilogram', 'piece') falling back to 'kilogram'
        $unitCode = $product->saleUnit ? $product->saleUnit->code : 'kilogram';

        // 2. Look up the translated symbol from lang/es/messages.php
        $unitSymbol = __("messages.measurement_units.{$unitCode}.symbol");
        if ($unitSymbol === "messages.measurement_units.{$unitCode}.symbol") {
            $unitSymbol = $unitCode;
        }

        $qty = (float) $this->weight;
        $formattedQuantity = "";

        // 3. Smart formatting rules (matching the receipt ticket logic)
        if ($qty >= 1.0) {
            // Whole numbers drop decimals; fractional numbers trim unnecessary trailing zeros (e.g., 1.500 -> 1.5)
            $formattedQty = (floor($qty) == $qty) ? (int)$qty : rtrim(rtrim(number_format($qty, 3, '.', ''), '0'), '.');
            $formattedQuantity = "{$formattedQty} {$unitSymbol}";
        } else {
            // Convert weights under 1kg into grams (e.g., 0.550 kg -> 550 g, 0.140 kg -> 140 g)
            $grams = round($qty * 1000);
            $formattedQuantity = "{$grams} g";
        }

        // Fallback to 'Abarrotes Todo En Uno' to match your Node.js default
        $storeName = $store->business->name ?? 'Abarrotes Todo En Uno';

        $payload = [
            'storeName' => $storeName,
            'productName' => $product->name,
            'quantity' => $formattedQuantity,
            'barcode' => $this->dynamicBarcode,
            'copies' => $this->copies,
        ];

        // Construct URL from your .env variables
        $host = env('PRINTER_SERVER_HOST', '127.0.0.1');
        $port = env('PRINTER_SERVER_PORT', '3100');
        $url = "http://{$host}:{$port}/api/printer/label/print";

        $response = Http::timeout(10)->post($url, $payload);

        if ($response->failed()) {
            $status = $response->status();
            $errorMsg = $response->json('message') ?? $response->body();

            Log::error("Label Printer Service Failed (Product ID: {$product->id})", [
                'status' => $status,
                'error' => $errorMsg,
            ]);

            // 422 is a validation error from your JS schema, 500/503 are hardware errors
            if ($status >= 500 || $status === 422) {
                throw new Exception("Label printer hardware/validation error: {$errorMsg}");
            }
            
            return;
        }

        Log::info("Label successfully sent to printer for Product ID: {$product->id}");
    }
}