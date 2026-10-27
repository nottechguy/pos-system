<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Department;
use App\Models\ProductCategory;

class ProductPackageController extends Controller
{
    public function index(Product $product)
    {
        $product->load(['components', 'saleUnit']);
        $departments = Department::where('is_active', true)->get();
        $categories = ProductCategory::where('is_active', true)->get();
        
        return view('admin.products.package.index', compact('product', 'departments', 'categories'));
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'component_product_id' => 'required|exists:products,id|different:' . $product->id,
            'quantity' => 'required|numeric|min:0.0001',
        ]);

        $product->components()->sync([
            $validated['component_product_id'] => ['quantity' => $validated['quantity']]
        ]);

        return redirect()->route('admin.products.pricing.index', $product->id)
                         ->with('success', 'El contenido del paquete se ha configurado correctamente. Por favor, establezca los precios.');
    }

    public function storeComponent(Request $request, Product $product)
    {
        $validated = $request->validate([
            'component_product_id' => 'required|exists:products,id|different:' . $product->id,
            'quantity' => 'required|numeric|min:0.0001',
        ]);

        $product->components()->syncWithoutDetaching([
            $validated['component_product_id'] => ['quantity' => $validated['quantity']]
        ]);

        return redirect()->route('admin.products.edit', $product->id)
                         ->with('success', 'Componente añadido al paquete correctamente.');
    }

    public function destroyComponent(Product $product, Product $component)
    {
        $product->components()->detach($component->id);

        return redirect()->route('admin.products.edit', $product->id)
                         ->with('success', 'Componente extraído del paquete correctamente.');
    }
}