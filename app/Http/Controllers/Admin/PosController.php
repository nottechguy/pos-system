<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CashShift;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductBarcode;
use App\Services\CompleteSaleService;
use App\Jobs\PrintSaleReceiptJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
public function index()
    {
        // Find an open shift for the current store/terminal
        $activeShift = CashShift::where('store_id', 1) // Or dynamically resolved store ID
            ->where('status', 'open')
            ->first();

        if (!$activeShift) {
            return redirect()->route('admin.shifts.create')
                             ->with('error', 'Please open a cash shift to access the POS.');
        }

        return view('admin.pos.index', compact('activeShift'));
    }

    public function search(Request $request)
    {
        $term = $request->input('term');
        $storeId = $request->input('store_id');
        $customerId = $request->input('customer_id');

        if (!$term) {
            return response()->json([]);
        }

        $searchBarcode = $term;
        $parsedWeight = null;
        
        if (strpos($term, '-') !== false) {
            $parts = explode('-', $term);
            $searchBarcode = $parts[0];
            $weightGrams = floatval($parts[1] ?? 0);
            $parsedWeight = $weightGrams > 0 ? ($weightGrams / 1000) : null;
        }

        // Find product IDs matching either the barcode partially/fully or SKU
        $matchingProductIds = ProductBarcode::where('barcode', 'LIKE', '%' . $searchBarcode . '%')
            ->pluck('product_id');

        $productsQuery = Product::with(['saleUnit', 'inventoryUnit', 'barcodes'])
            ->where(function($q) use ($term, $matchingProductIds) {
                if ($matchingProductIds->isNotEmpty()) {
                    $q->whereIn('id', $matchingProductIds);
                }
                $q->orWhere('sku', 'LIKE', '%' . $term . '%')
                  ->orWhere('name', 'LIKE', '%' . $term . '%');
            });

        $priceListId = null;
        if ($customerId) {
            $customer = Customer::find($customerId);
            $priceListId = $customer->price_list_id ?? null;
        }

        $products = $productsQuery->with('quantityRules')->limit(20)->get()->map(function ($product) use ($storeId, $customerId, $parsedWeight, $priceListId) {
            
            $price = null;

            if ($customerId) {
                $customPriceRecord = DB::table('customer_product_prices')
                    ->where('customer_id', $customerId)
                    ->where('product_id', $product->id)
                    ->value('price');

                if (!is_null($customPriceRecord)) {
                    $price = $customPriceRecord;
                }
            }

            if (is_null($price)) {
                $priceQuery = DB::table('product_prices')
                    ->join('price_lists', 'product_prices.price_list_id', '=', 'price_lists.id')
                    ->where('product_prices.product_id', $product->id);

                if ($priceListId) {
                    $priceQuery->where('price_lists.id', $priceListId);
                } else {
                    $priceQuery->where('price_lists.store_id', $storeId);
                }

                $price = $priceQuery->value('product_prices.sale_price');
            }
            
            if (is_null($price)) {
                $price = DB::table('product_prices')
                    ->join('price_lists', 'product_prices.price_list_id', '=', 'price_lists.id')
                    ->where('product_prices.product_id', $product->id)
                    ->where('price_lists.store_id', $storeId)
                    ->value('product_prices.sale_price') ?? 0.00;
            }

            $unitCode = $product->saleUnit ? $product->saleUnit->code : 'kilogram';
            $unitSymbol = __("messages.measurement_units.{$unitCode}.symbol") ?? $unitCode;
            $primaryBarcode = $product->barcodes->where('is_primary', true)->first()->barcode ?? $product->sku;

            return [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'barcode' => $primaryBarcode,
                'price' => (float) $price,
                'unit' => $unitSymbol,
                'allows_fraction' => $product->allows_fractional_qty,
                'default_weight' => $parsedWeight,
                'quantity_rules' => $product->quantityRules->where('is_active', true)->values(),
            ];
        });

        return response()->json($products);
    }

    public function searchCustomer(Request $request)
    {
        $term = $request->input('term');
        if (!$term) return response()->json([]);

        $customers = Customer::where('first_name', 'LIKE', "%{$term}%")
            ->orWhere('last_name', 'LIKE', "%{$term}%")
            ->orWhere('business_name', 'LIKE', "%{$term}%")
            ->orWhere('phone', 'LIKE', "%{$term}%")
            ->orWhere('email', 'LIKE', "%{$term}%")
            ->limit(10)
            ->get()
            ->map(function($c) {
                return [
                    'id' => $c->id,
                    'name' => $c->display_name,
                    'phone' => $c->phone,
                    'email' => $c->email,
                    'type' => $c->customer_type,
                    'has_credit' => $c->creditAccount ? true : false
                ];
            });

        return response()->json($customers);
    }

    public function recalculateCart(Request $request)
    {
        $customerId = $request->input('customer_id');
        $storeId = $request->input('store_id');
        $items = $request->input('items', []);

        $priceListId = null;
        if ($customerId) {
            $customer = Customer::find($customerId);
            $priceListId = $customer->price_list_id ?? null;
        }

        $updatedPrices = [];
        foreach ($items as $productId) {
            $price = null;

            // 1. Check for custom customer-specific product price override first
            if ($customerId) {
                $customPriceRecord = DB::table('customer_product_prices')
                    ->where('customer_id', $customerId)
                    ->where('product_id', $productId)
                    ->value('price');

                if (!is_null($customPriceRecord)) {
                    $price = $customPriceRecord;
                }
            }

            // 2. Fallback to Price List
            if (is_null($price)) {
                $priceQuery = DB::table('product_prices')
                    ->join('price_lists', 'product_prices.price_list_id', '=', 'price_lists.id')
                    ->where('product_prices.product_id', $productId);

                if ($priceListId) {
                    $priceQuery->where('price_lists.id', $priceListId);
                } else {
                    $priceQuery->where('price_lists.store_id', $storeId);
                }

                $price = $priceQuery->value('product_prices.sale_price');
            }
            
            // 3. Fallback to store default
            if (is_null($price)) {
                $price = DB::table('product_prices')
                    ->join('price_lists', 'product_prices.price_list_id', '=', 'price_lists.id')
                    ->where('product_prices.product_id', $productId)
                    ->where('price_lists.store_id', $storeId)
                    ->value('product_prices.sale_price') ?? 0.00;
            }

            $updatedPrices[$productId] = (float) $price;
        }

        return response()->json($updatedPrices);
    }

    public function searchPromotions(Request $request)
    {
        $term = $request->input('term', '');
        
        $promotions = \App\Models\Promotion::with(['products.barcodes', 'products.saleUnit', 'products' => function($q) {
            $q->withPivot('promotional_price', 'discount_value');
        }])
            ->where('is_active', true)
            ->where(function($q) use ($term) {
                $q->where('name', 'LIKE', "%{$term}%")
                  ->orWhere('description', 'LIKE', "%{$term}%");
            })
            ->get()
            ->map(function($promo) {
                return [
                    'id' => $promo->id,
                    'name' => $promo->name,
                    'description' => $promo->description,
                    'promotion_type' => $promo->promotion_type,
                    'promotional_price' => (float) $promo->promotional_price,
                    'minimum_quantity' => (float) $promo->minimum_quantity,
                    'products' => $promo->products->map(function($product) use ($promo) {
                        $unitCode = $product->saleUnit ? $product->saleUnit->code : 'piece';
                        $unitSymbol = __("messages.measurement_units.{$unitCode}.symbol") ?? $unitCode;
                        $primaryBarcode = $product->barcodes->where('is_primary', true)->first()->barcode ?? $product->sku;
                        
                        $pivotPromoPrice = $product->pivot->promotional_price;
                        $finalPrice = $product->price; 

                        if ($pivotPromoPrice !== null && $pivotPromoPrice > 0) {
                            $finalPrice = (float) $pivotPromoPrice;
                        } elseif ($promo->promotional_price > 0 && $promo->promotion_type === 'pack') {
                            $finalPrice = (float) $promo->promotional_price / max(1, $promo->products->count());
                        }

                        return [
                            'id' => $product->id,
                            'name' => $product->name,
                            'sku' => $product->sku,
                            'barcode' => $primaryBarcode,
                            'price' => $finalPrice,
                            'original_price' => $product->price,
                            'unit' => $unitSymbol,
                            'allows_fraction' => $product->allows_fractional_qty,
                            'promotion_id' => $promo->id,
                            'promotion_name' => $promo->name
                        ];
                    })
                ];
            });

        return response()->json($promotions);
    }

    public function checkout(Request $request, CompleteSaleService $completeSaleService)
    {
        $checkoutStartedNs = hrtime(true);

        $payload = $request->validate([
            'store_id' => 'required|integer',
            'terminal_id' => 'required|integer',
            'customer_id' => 'nullable|integer|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'payments' => 'required|array|min:1',
            'payments.*.payment_method_id' => 'required|integer',
            'payments.*.amount' => 'required|numeric',
            'payments.*.received_amount' => 'required|numeric',
            'mode' => 'sometimes|string|in:Ticket,Nota de Venta', // Optional mode validation
        ]);

        try {
            $serviceStartedNs = hrtime(true);

            $sale = $completeSaleService->execute($payload, auth()->id());

            $saleServiceMs = round(
                (hrtime(true) - $serviceStartedNs) / 1_000_000,
                2
            );

            $mode = $request->input('mode', 'Ticket');

            $dispatchStartedNs = hrtime(true);

            $printJob = new PrintSaleReceiptJob($sale->id, $mode);

            dispatch($printJob);

            $dispatchMs = round(
                (hrtime(true) - $dispatchStartedNs) / 1_000_000,
                2
            );

            Log::info('pos.checkout.completed', [
                'sale_id' => $sale->id,
                'ticket_number' => $sale->number,
                'print_trace_id' => $printJob->traceId,
                'sale_service_ms' => $saleServiceMs,
                'dispatch_ms' => $dispatchMs,
                'checkout_elapsed_ms' => round(
                    (hrtime(true) - $checkoutStartedNs) / 1_000_000,
                    2
                ),
            ]);

            return response()->json([
                'success' => true,
                'ticket_number' => $sale->number,
                'change' => $sale->payments->first()->change_amount ?? 0,
            ]);
        } catch (\Exception $e) {
            Log::error('pos.checkout.failed', [
                'error' => $e->getMessage(),
                'checkout_elapsed_ms' => round(
                    (hrtime(true) - $checkoutStartedNs) / 1_000_000,
                    2
                ),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}