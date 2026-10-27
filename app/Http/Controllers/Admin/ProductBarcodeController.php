<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductBarcode;
use App\Models\MeasurementUnit;

class ProductBarcodeController extends Controller
{
    public function index(Product $product)
    {
        $product->load(['barcodes.unit', 'saleUnit']);
        $units = MeasurementUnit::all();

        return view('admin.products.barcodes.index', compact('product', 'units'));
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'barcode' => 'required|string|max:50|unique:product_barcodes,barcode',
            'unit_id' => 'required|exists:measurement_units,id',
            'conversion_factor' => 'required|numeric|min:0.000001',
            'is_primary' => 'boolean',
        ]);

        $isPrimary = $request->has('is_primary');

        // If this barcode is marked primary, unset any existing primary barcodes for this product
        if ($isPrimary) {
            $product->barcodes()->update(['is_primary' => false]);
        }

        // If this is the very first barcode, force it to be primary automatically
        if ($product->barcodes()->count() === 0) {
            $isPrimary = true;
        }

        ProductBarcode::create([
            'product_id' => $product->id,
            'unit_id' => $validated['unit_id'],
            'barcode' => $validated['barcode'],
            'conversion_factor' => $validated['conversion_factor'],
            'is_primary' => $isPrimary,
        ]);

        return redirect()->route('admin.products.barcodes.index', $product->id)
                         ->with('success', 'Código de barras añadido correctamente.');
    }

    public function update(Request $request, Product $product, ProductBarcode $barcode)
    {
        if ($barcode->product_id !== $product->id) {
            abort(403);
        }

        $validated = $request->validate([
            'barcode' => 'required|string|max:50|unique:product_barcodes,barcode,' . $barcode->id,
            'unit_id' => 'required|exists:measurement_units,id',
            'conversion_factor' => 'required|numeric|min:0.000001',
            'is_primary' => 'boolean',
        ]);

        $isPrimary = $request->has('is_primary');

        // If this barcode is marked primary, unset any existing primary barcodes for this product
        if ($isPrimary) {
            $product->barcodes()->where('id', '!=', $barcode->id)->update(['is_primary' => false]);
        }

        // Prevent un-primarying if it's the only barcode left
        if (!$isPrimary && $product->barcodes()->count() === 1) {
            $isPrimary = true;
        }

        $barcode->update([
            'unit_id' => $validated['unit_id'],
            'barcode' => $validated['barcode'],
            'conversion_factor' => $validated['conversion_factor'],
            'is_primary' => $isPrimary,
        ]);

        return redirect()->route('admin.products.barcodes.index', $product->id)
                         ->with('success', 'Código de barras actualizado correctamente.');
    }

    public function destroy(Product $product, ProductBarcode $barcode)
    {
        if ($barcode->product_id === $product->id) {
            // Prevent deleting the primary barcode if others exist
            if ($barcode->is_primary && $product->barcodes()->count() > 1) {
                return back()->withErrors(['barcode' => 'No es posible eliminar el código de barras principal. Primero, establezca otro código de barras como principal.']);
            }

            $barcode->delete();

            return redirect()->route('admin.products.barcodes.index', $product->id)
                             ->with('success', 'Código de barras eliminado correctamente.');
        }

        abort(403);
    }
}