<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\PriceList;
use App\Models\ProductPrice;
use App\Models\ProductQuantityRule;

class ProductPricingController extends Controller
{
    public function index(Product $product)
    {
        // Fetch all active price lists to display inputs for each
        $priceLists = PriceList::all(); // Assuming PriceList has standard fields
        
        // Load existing prices keyed by price_list_id for easy lookup in the view
        $existingPrices = ProductPrice::where('product_id', $product->id)
                            ->get()
                            ->keyBy('price_list_id');

        // Load existing bulk rules
        $rules = ProductQuantityRule::where('product_id', $product->id)
                    ->with('priceList')
                    ->orderBy('minimum_quantity', 'asc')
                    ->get();

        return view('admin.products.pricing', compact('product', 'priceLists', 'existingPrices', 'rules'));
    }

    public function storePrices(Request $request, Product $product)
    {
        // Expecting prices[price_list_id][sale_price], etc.
        $validated = $request->validate([
            'prices' => 'required|array',
            'prices.*.cost_price' => 'nullable|numeric|min:0',
            'prices.*.sale_price' => 'nullable|numeric|min:0',
            'prices.*.discount' => 'nullable|numeric|min:0',
        ]);

        foreach ($validated['prices'] as $priceListId => $priceData) {
            $salePrice = $priceData['sale_price'] ?? null;
            $costPrice = $priceData['cost_price'] ?? 0;
            $discount = $priceData['discount'] ?? 0;

            if (is_numeric($salePrice)) {
                
                // Auto-calculate Margin Percentage: ((Sale - Cost) / Sale) * 100
                $margin = 0;
                if ($salePrice > 0) {
                    $margin = (($salePrice - $costPrice) / $salePrice) * 100;
                }

                ProductPrice::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'price_list_id' => $priceListId,
                    ],
                    [
                        'cost_price' => $costPrice,
                        'sale_price' => $salePrice,
                        'discount' => $discount,
                        'margin' => $margin,
                        'valid_from' => now(), 
                        'created_by_user_id' => auth()->id(),
                    ]
                );
            } else {
                ProductPrice::where('product_id', $product->id)
                            ->where('price_list_id', $priceListId)
                            ->delete();
            }
        }

        return redirect()->route('admin.inventory.stock.create', $product->id)
                         ->with('success', 'Precios base guardados correctamente. Ahora, establezca el saldo de existencias inicial.');
    }

    public function storeRule(Request $request, Product $product)
    {
        $validated = $request->validate([
            'price_list_id' => 'nullable|exists:price_lists,id',
            'name' => 'required|string|max:255',
            'rule_type' => 'required|string|max:50', // e.g., 'fixed_price', 'percentage', 'discount'
            'minimum_quantity' => 'required|numeric|min:0.01',
            'discount_value' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['product_id'] = $product->id;
        $validated['created_by_user_id'] = auth()->id();
        $validated['is_active'] = $request->has('is_active');
        // Defaulting repeatable to true for standard bulk (e.g., 3 for $10, 6 for $20)
        $validated['repeatable'] = $request->has('repeatable') ?? true;
        $validated['priority'] = 1; // Default priority

        ProductQuantityRule::create($validated);

        return redirect()->route('admin.products.pricing.index', $product->id)
                         ->with('success', 'Regla de precios añadida correctamente.');
    }

    public function destroyRule(Product $product, ProductQuantityRule $rule)
    {
        // Ensure the rule actually belongs to the product
        if ($rule->product_id === $product->id) {
            $rule->delete();
            return redirect()->route('admin.products.pricing.index', $product->id)
                             ->with('success', 'Regla de precios eliminada correctamente.');
        }
        
        abort(403);
    }
}