<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductBoxController extends Controller
{
    public function index(Product $product)
    {
        $product->load(['components', 'saleUnit']);
        $departments = \App\Models\Department::where('is_active', true)->get();
        $categories = \App\Models\ProductCategory::where('is_active', true)->get();
        
        return view('admin.products.box.index', compact('product', 'departments', 'categories'));
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
                         ->with('success', 'Contenido de la caja configurado correctamente. Por favor, establece los precios.');
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
                         ->with('success', 'Componente añadido a la caja correctamente.');
    }

    public function destroyComponent(Product $product, Product $component)
    {
        $product->components()->detach($component->id);

        return redirect()->route('admin.products.edit', $product->id)
                         ->with('success', 'Componente extraído de la caja correctamente.');
    }
}