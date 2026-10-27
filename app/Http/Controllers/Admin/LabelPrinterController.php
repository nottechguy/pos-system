<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Product;
use App\Jobs\PrintProductLabelJob;
use Illuminate\Http\Request;

class LabelPrinterController extends Controller
{
    public function index()
    {
        $departments = Department::where('is_active', true)->get();
        return view('admin.label_printer.index', compact('departments'));
    }

    public function search(Request $request)
    {
        $term = $request->input('term');
        $departmentId = $request->input('department_id');
        $storeId = session('store_id', 1); // fallback store ID

        $query = Product::with(['barcodes', 'currentPrice' => function($query) use ($storeId) {
                $query->join('price_lists', 'product_prices.price_list_id', '=', 'price_lists.id')
                      ->where('price_lists.store_id', $storeId);
            }])
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
            $primaryBarcode = $product->barcodes->where('is_primary', true)->first() 
                ?? $product->barcodes->first();

            return [
                'id' => $product->id,
                'name' => $product->name,
                'barcode' => $primaryBarcode ? $primaryBarcode->barcode : $product->sku,
                'sale_price' => (float) $product->sale_price,
                'allows_fractional_qty' => $product->allows_fractional_qty,
                'sale_unit_code' => $product->saleUnit ? $product->saleUnit->code : 'piece',
            ];
        });

        return response()->json($products);
    }

    public function print(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'weight'     => 'required|numeric|min:0.001', // Ensures a valid positive quantity/weight
            'copies'     => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $storeId = session('store_id', 1);
        $weight  = (float) $validated['weight'];
        $copies  = (int) $validated['copies'];

        $baseBarcode = $product->barcodes->where('is_primary', true)->first()?->barcode ?? $product->sku;

        // Dynamic barcode generation applies only to weighted/fractional items
        if ($product->allows_fractional_qty && $weight > 0) {
            $weightInGrams = (int) round($weight * 1000);
            $dynamicBarcode = sprintf("%s-%04d", $baseBarcode, $weightInGrams);
        } else {
            $dynamicBarcode = $baseBarcode;
        }

        PrintProductLabelJob::dispatchSync(
            $product->id,
            $storeId,
            $weight,
            $dynamicBarcode,
            $copies
        );

        return response()->json([
            'success' => true,
            'message' => '¡Etiqueta enviada a impresión exitosamente!'
        ]);
    }
}