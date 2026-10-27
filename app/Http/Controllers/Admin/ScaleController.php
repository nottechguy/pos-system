<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Product;
use Illuminate\Http\Request;

class ScaleController extends Controller
{
    public function index()
    {
        $departments = Department::where('is_active', true)->get();
        return view('admin.scale.index', compact('departments'));
    }

    public function search(Request $request)
    {
        $term = $request->input('term');
        $departmentId = $request->input('department_id');
        
        $storeId = session('store_id', 1); // fallback store ID

        $query = Product::with(['barcodes', 'currentPrice' => function($query) use ($storeId) {
                // If your product_prices tie to a price list linked to a store, scope it here if needed
                $query->join('price_lists', 'product_prices.price_list_id', '=', 'price_lists.id')
                      ->where('price_lists.store_id', $storeId);
            }])
            ->where('allows_fractional_qty', true) // Only show scale-ready products
            ->when($departmentId, function ($q) use ($departmentId) {
                return $q->where('department_id', $departmentId);
            });

        if ($term) {
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('sku', 'like', "%{$term}%")
                  ->orWhereHas('barcodes', function ($q2) use ($term) {
                      $q2->where('barcode', 'like', "%{$term}%");
                  });
            });
        }

        $products = $query->take(20)->get()->map(function ($product) {
            // Find the primary barcode or fallback to the first barcode/sku
            $primaryBarcode = $product->barcodes->where('is_primary', true)->first() 
                ?? $product->barcodes->first();

            return [
                'id' => $product->id,
                'name' => $product->name,
                'barcode' => $primaryBarcode ? $primaryBarcode->barcode : $product->sku,
                'sale_price' => (float) $product->sale_price, // Uses your model's built-in accessor!
            ];
        });

        return response()->json($products);
    }
}