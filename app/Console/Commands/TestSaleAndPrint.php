<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Store;
use App\Models\PosTerminal;
use App\Models\Product;
use App\Models\ProductBarcode;
use App\Models\ProductQuantityRule;
use App\Models\PaymentMethod;
use App\Models\CashShift;
use App\Models\DocumentSequence;
use App\Services\OpenShiftService;
use App\Services\CompleteSaleService;
use App\Jobs\PrintSaleReceiptJob;
use Exception;

class TestSaleAndPrint extends Command
{
    protected $signature = 'test:sale-print 
                            {--products= : JSON object mapping barcodes to quantities/weights/prices} 
                            {--receivedAmount= : The amount of cash received from the customer}
                            {--mode=Ticket : Print mode: "Ticket" or "Nota de Venta"}';
                            
    protected $description = 'Simulates a sale and dispatches the print job to the Node.js server.';

    public function handle(
        OpenShiftService $openShiftService, 
        CompleteSaleService $completeSaleService
    ): int {
        $this->info('Starting End-to-End POS Test...');

        try {
            // 1. Fetch Seeded Environment Data
            $admin = User::where('email', 'sergio@abarrotestodoenuno')->firstOrFail();
            $store = Store::where('code', 'ST01')->firstOrFail();
            $terminal = PosTerminal::where('code', 'TERM01')->firstOrFail();
            $paymentMethod = PaymentMethod::where('code', 'cash')->firstOrFail();

            // 2. Parse Products Flag or use Default
            $productsJson = $this->option('products');
            
            if ($productsJson) {
                $requestedProducts = json_decode($productsJson, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new Exception("Invalid JSON provided for --products: " . json_last_msg());
                }
            } else {
                $requestedProducts = [
                    '041789901980' => ['quantity' => 1]
                ];
            }

            $items = [];
            foreach ($requestedProducts as $barcode => $data) {
                $barcodeRecord = ProductBarcode::with('product')->where('barcode', $barcode)->first();
                
                if (!$barcodeRecord) {
                    $this->warn("Barcode '{$barcode}' not found. Skipping.");
                    continue;
                }

                $product = $barcodeRecord->product;

                // Fetch unit price early to calculate quantity if 'price' is provided
                $unitPrice = DB::table('product_prices')
                    ->join('price_lists', 'product_prices.price_list_id', '=', 'price_lists.id')
                    ->where('product_prices.product_id', $product->id)
                    ->where('price_lists.store_id', $store->id)
                    ->value('product_prices.sale_price') ?? 0.00;

                // Determine final quantity based on provided flag attributes
                if (isset($data['price'])) {
                    // Convert target price into fractional weight based on the unit price
                    $quantity = $unitPrice > 0 ? ((float) $data['price'] / $unitPrice) : 1;
                    $this->info("Calculated quantity " . number_format($quantity, 3) . " for price {$data['price']} on {$product->name}");
                } elseif (isset($data['weight'])) {
                    $quantity = (float) $data['weight'];
                } else {
                    $quantity = (float) ($data['quantity'] ?? 1);
                }

                $items[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                ];
            }

            if (empty($items)) {
                $this->error("No valid products could be resolved for this sale.");
                return Command::FAILURE;
            }

            // 3. Ensure an Open Shift Exists
            $shift = CashShift::where('terminal_id', $terminal->id)->where('status', 'open')->first();
            
            if (!$shift) {
                $this->warn('No open shift found. Opening a new shift with $500 float...');
                $shift = $openShiftService->execute(
                    terminal: $terminal,
                    openingCash: 500.00,
                    userId: $admin->id,
                    notes: 'Test script opening shift'
                );
                $this->info("Shift opened successfully! (ID: {$shift->id})");
            } else {
                $this->info("Found existing open shift (ID: {$shift->id}).");
            }

            // 3.5 Ensure Document Sequence Exists
            $sequence = DocumentSequence::firstOrCreate(
                ['store_id' => $store->id, 'document_type' => 'sale'],
                ['prefix' => 'TICK-', 'next_number' => 1, 'padding' => 6] 
            );

            // 4. Calculate dynamic total simulating the frontend cart logic
            $this->info('Calculating cart totals dynamically based on current DB prices and rules...');
            
            $calculatedTotal = 0;
            $payloadItems = [];

            foreach ($items as $item) {
                $unitPrice = $item['unit_price'];
                $lineGross = $unitPrice * $item['quantity'];
                $itemDiscount = 0;

                $bestRule = ProductQuantityRule::where('product_id', $item['product']->id)
                    ->where('is_active', true)
                    ->where('minimum_quantity', '<=', $item['quantity'])
                    ->orderBy('priority', 'desc')
                    ->orderBy('minimum_quantity', 'desc')
                    ->first();

                if ($bestRule) {
                    $timesToApply = 1;
                    $groupQty = $bestRule->group_quantity > 0 ? $bestRule->group_quantity : $bestRule->minimum_quantity;
                    
                    if ($bestRule->repeatable) {
                        $timesToApply = floor($item['quantity'] / $groupQty);
                    }

                    if ($timesToApply > 0) {
                        $affectedQty = $timesToApply * $groupQty;
                        
                        if ($bestRule->rule_type === 'percentage') {
                            $itemDiscount = ($unitPrice * $affectedQty) * ($bestRule->discount_value / 100);
                        } elseif ($bestRule->rule_type === 'unit_discount') {
                            $itemDiscount = $bestRule->discount_value * $affectedQty;
                        } elseif ($bestRule->rule_type === 'fixed_price') {
                            $standardPriceForAffected = $affectedQty * $unitPrice;
                            $discountedPriceForAffected = $timesToApply * $bestRule->discount_value;
                            $itemDiscount = max(0, $standardPriceForAffected - $discountedPriceForAffected);
                        }
                    }
                }

                $calculatedTotal += ($lineGross - $itemDiscount);

                $payloadItems[] = [
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                ];
            }

            // Determine the Received Amount
            $providedReceivedAmount = $this->option('receivedAmount');

            if ($providedReceivedAmount !== null) {
                $receivedAmount = (float) $providedReceivedAmount;
            } else {
                $receivedAmount = ceil($calculatedTotal / 100) * 500; 
                if ($receivedAmount == 0) $receivedAmount = 100;
            }

            if ($receivedAmount < $calculatedTotal) {
                $this->warn("Warning: The received amount (\${$receivedAmount}) is less than the calculated total (\${$calculatedTotal}).");
            }

            $salePayload = [
                'store_id' => $store->id,
                'terminal_id' => $terminal->id,
                'items' => $payloadItems,
                'payments' => [
                    [
                        'payment_method_id' => $paymentMethod->id,
                        'amount' => $calculatedTotal,
                        'received_amount' => $receivedAmount 
                    ]
                ]
            ];

            // 5. Execute the Sale Service
            $sale = $completeSaleService->execute($salePayload, $admin->id);
            $this->info("Sale committed to database! Ticket #: {$sale->number}");
            $this->info("Total: \${$sale->total} | Change: \${$sale->payments->first()->change_amount}");

            // 6. Dispatch the Print Job with the Mode
            $mode = $this->option('mode');
            $this->info("Dispatching print job ({$mode} mode) to Node.js server...");
            
            PrintSaleReceiptJob::dispatchSync($sale->id, $mode);

            $this->info('✅ Test complete! Check your physical printer.');
            
            return Command::SUCCESS;

        } catch (Exception $e) {
            $this->error("Test Failed: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}