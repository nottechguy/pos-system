<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Store;
use App\Models\Product;
use App\Models\ProductBarcode;
use App\Jobs\PrintProductLabelJob;
use Exception;

class TestLabelPrint extends Command
{
    // Accept barcode and weight arguments with defaults for testing
    protected $signature = 'test:label-print {--barcode=QUESOOAXACA} {--weight=0.550} {--copies=1}';
    protected $description = 'Simulates a scale weighing a fractional product and prints a label.';

    public function handle(): int 
    {
        $this->info('Starting Label Print Test...');

        try {
            // Assuming ST01 is your primary test store
            $store = Store::where('code', 'ST01')->firstOrFail();
            $baseBarcode = $this->option('barcode');
            $weight = (float) $this->option('weight');
            $copies = $this->option('copies');

            // 1. Resolve Product
            $productBarcode = ProductBarcode::where('barcode', $baseBarcode)->first();
            
            if ($productBarcode) {
                $product = $productBarcode->product;
            } else {
                // Fallback if QUESOOAXACA barcode isn't seeded yet
                $product = Product::where('sku', $baseBarcode)
                    ->orWhere('name', 'LIKE', '%Oaxaca%')
                    ->firstOrFail();
            }

            if (!$product->allows_fractional_qty) {
                $this->warn("Warning: Product '{$product->name}' does not have allows_fractional_qty enabled.");
            }

            // 2. Calculate Pricing (For console display context)
            $unitPrice = DB::table('product_prices')
                ->join('price_lists', 'product_prices.price_list_id', '=', 'price_lists.id')
                ->where('product_prices.product_id', $product->id)
                ->where('price_lists.store_id', $store->id)
                ->value('product_prices.sale_price') ?? 0.00;

            $totalPrice = $unitPrice * $weight;
            
            $this->info(sprintf(
                "Product: %s | Unit Price: $%.2f | Weight: %.3f | Total: $%.2f", 
                $product->name, $unitPrice, $weight, $totalPrice
            ));

            // 3. Generate Dynamic Barcode
            // A common retail standard for in-store weighed items is prefixing with 21 or 22, 
            // followed by the item ID, and the price or weight. 
            // For this custom implementation, we will append the weight in grams to the base barcode.
            // Example output: QUESOOAXACA-0550
            $weightInGrams = (int) round($weight * 1000);
            $dynamicBarcode = sprintf("%s-%04d", $baseBarcode, $weightInGrams);

            $this->info("Generated Dynamic Barcode: {$dynamicBarcode}");
            $this->info('Dispatching label print job to Node.js server...');

            // 4. Dispatch the Job synchronously for the CLI test
            PrintProductLabelJob::dispatchSync(
                $product->id,
                $store->id,
                $weight,
                $dynamicBarcode,
                $copies
            );

            $this->info('✅ Label test complete! Check your Holyhah printer.');

            return Command::SUCCESS;

        } catch (Exception $e) {
            $this->error("Test Failed: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}