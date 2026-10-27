<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Business;
use App\Models\Store;
use App\Models\Department;
use App\Models\ProductCategory;
use App\Models\MeasurementUnit;
use App\Models\Product;
use App\Models\ProductBarcode;
use App\Models\ProductPrice;
use App\Models\PriceList;
use App\Models\InventoryBalance;
use App\Models\InventoryMovement;
use App\Services\BarcodeLabelService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CatalogSyncController extends Controller
{
    public function sync(Request $request, BarcodeLabelService $labelService)
    {
        $data = $request->validate([
            'department_code' => 'required|string',
            'department_name' => 'required|string',
            'barcode' => 'required|string',
            'box_component_barcode' => 'nullable|string',
            'box_quantity' => 'nullable|numeric|min:0.0001',
            'name' => 'required|string',
            'category_name' => 'required|string',
            'product_type' => 'required|string',
            'sale_unit_code' => 'required|string',
            'inventory_unit_code' => 'required|string',
            'cost_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'allows_fractional_qty' => 'required|boolean',
            'update_mode' => 'required|boolean',
        ]);

        return DB::transaction(function () use ($data, $labelService) {
            $business = Business::first();
            $priceList = PriceList::where('is_default', true)->first();
            $store = Store::where('business_id', $business->id)->first();

            // 1. Resolve Department & Category
            $department = Department::firstOrCreate(
                ['code' => $data['department_code']],
                ['business_id' => $business->id, 'name' => $data['department_name'], 'is_active' => true]
            );

            $category = ProductCategory::firstOrCreate(
                ['department_id' => $department->id, 'name' => $data['category_name']],
                ['business_id' => $business->id, 'code' => Str::upper(Str::limit(Str::slug($data['category_name']), 5, '')), 'is_active' => true]
            );

            // 2. Resolve Units
            $saleUnit = MeasurementUnit::where('code', $data['sale_unit_code'])->firstOrFail();
            $invUnit = MeasurementUnit::where('code', $data['inventory_unit_code'])->firstOrFail();


            // 3. Find Product by Barcode and Name combination (or create new if variant/product doesn't exist yet)
            $existingBarcodeRecord = ProductBarcode::where('barcode', $data['barcode'])
                ->whereHas('product', function($q) use ($data) {
                    $q->where('name', $data['name']);
                })
                ->first();

            $product = $existingBarcodeRecord ? $existingBarcodeRecord->product : null;

            if ($product && !$data['update_mode']) {
                return response()->json(['message' => 'Skipped existing product'], 200);
            }

            if ($product) {
                $product->update([
                    'name' => $data['name'],
                    'department_id' => $department->id,
                    'category_id' => $category->id,
                    'sale_unit_id' => $saleUnit->id,
                    'inventory_unit_id' => $invUnit->id,
                    'allows_fractional_qty' => $data['allows_fractional_qty']
                ]);
            } else {
                $product = Product::create([
                    'business_id' => $business->id,
                    'department_id' => $department->id,
                    'category_id' => $category->id,
                    'inventory_unit_id' => $invUnit->id,
                    'sale_unit_id' => $saleUnit->id,
                    'sku' => 'SKU-' . strtoupper(Str::random(6)),
                    'name' => $data['name'],
                    'product_type' => $data['product_type'],
                    'allows_fractional_qty' => $data['allows_fractional_qty'],
                    'is_active' => true,
                ]);

                ProductBarcode::create([
                    'product_id' => $product->id,
                    'unit_id' => $saleUnit->id,
                    'barcode' => $data['barcode'],
                    'conversion_factor' => 1,
                    'is_primary' => true,
                ]);
            }

            // 4. Handle Box / Package Components Mapping if provided
            if (!empty($data['box_component_barcode']) && !empty($data['box_quantity'])) {
                $componentBarcodeRecord = ProductBarcode::where('barcode', $data['box_component_barcode'])->first();
                if ($componentBarcodeRecord) {
                    $product->components()->sync([
                        $componentBarcodeRecord->product_id => ['quantity' => $data['box_quantity']]
                    ]);
                }
            }

            // 5. Upsert Price
            ProductPrice::updateOrCreate(
                ['product_id' => $product->id, 'price_list_id' => $priceList->id],
                [
                    'cost_price' => $data['cost_price'],
                    'sale_price' => $data['sale_price'],
                    'margin' => $data['sale_price'] > 0 ? (($data['sale_price'] - $data['cost_price']) / $data['sale_price']) * 100 : 0,
                    'valid_from' => now(),
                ]
            );

            // 6. Ensure Minimum Inventory of 1000
            if ($store) {
                $balance = InventoryBalance::where('store_id', $store->id)
                    ->where('product_id', $product->id)
                    ->first();

                $currentAvailable = $balance ? $balance->quantity_available : 0;
                $targetMinimum = 1000;

                if ($currentAvailable < $targetMinimum) {
                    $qtyToAdd = $targetMinimum - $currentAvailable;
                    $currentOnHand = $balance ? $balance->quantity_on_hand : 0;
                    $newOnHand = $currentOnHand + $qtyToAdd;
                    $newVersion = $balance ? $balance->version + 1 : 1;

                    if ($balance) {
                        InventoryBalance::where('store_id', $store->id)
                            ->where('product_id', $product->id)
                            ->update([
                                'quantity_on_hand' => $newOnHand,
                                'version' => $newVersion,
                            ]);
                    } else {
                        InventoryBalance::insert([
                            'store_id' => $store->id,
                            'product_id' => $product->id,
                            'quantity_on_hand' => $newOnHand,
                            'quantity_reserved' => 0,
                            'version' => $newVersion,
                        ]);
                    }

                    InventoryMovement::create([
                        'uuid' => (string) Str::uuid(),
                        'store_id' => $store->id,
                        'product_id' => $product->id,
                        'user_id' => null,
                        'shift_id' => null,
                        'movement_type' => 'adjustment_in',
                        'quantity' => $qtyToAdd,
                        'unit_cost' => $data['cost_price'],
                        'balance_after' => $newOnHand,
                        'reference_type' => Product::class,
                        'reference_id' => $product->id,
                        'reason' => 'Sync enforced minimum stock of 1000',
                        'occurred_at' => now(),
                    ]);
                }
            }

            // 7. Generate Label if Fractional Item
            if ($data['allows_fractional_qty']) {
                try {
                    $labelService->generate(
                        businessName: strtoupper($business->name), 
                        productName: $product->name, 
                        barcodeText: $data['barcode']
                    );
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::warning("Failed to generate label for {$data['barcode']}: " . $e->getMessage());
                }
            }

            return response()->json(['message' => 'Product synced successfully'], 200);
        });
    }
}