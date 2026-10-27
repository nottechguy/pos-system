<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Business;
use App\Models\Department;
use App\Models\ProductCategory;
use App\Models\MeasurementUnit;
use App\Models\ProductBarcode;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller {

    public function index(Request $request) {
        $query = Product::with(['department', 'category', 'saleUnit', 'currentPrice']);

        // 1. Handle barcode or text search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhereHas('barcodes', function($subQ) use ($search) {
                      $subQ->where('barcode', $search);
                  });
            });
        }

        // 2. Handle Attribute Checkbox Filters
        // Expected filters array format: ?filters[is_active]=1, ?filters[allows_fractional_qty]=1, etc.
        if ($request->filled('filters') && is_array($request->filters)) {
            foreach ($request->filters as $attribute => $value) {
                // Only permit filtering by safe boolean/attribute columns to prevent SQL injection
                $allowedAttributes = ['is_active', 'allows_fractional_qty', 'track_inventory', 'allow_negative_inventory'];
                
                if (in_array($attribute, $allowedAttributes)) {
                    $query->where($attribute, filter_var($value, FILTER_VALIDATE_BOOLEAN));
                }
            }
        }

        // Optional department filter if selected via dropdown/filters
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $products = $query->paginate(70)->withQueryString();
        
        // Fetch active departments for the filter panel dropdown/options if needed
        $departments = Department::where('is_active', true)->get();

        return view('admin.products.index', compact('products', 'departments'));
    }

    public function show(Product $product) {
        $product->load([
            'department', 
            'category', 
            'saleUnit', 
            'inventoryUnit', 
            'barcodes', 
            'suppliers'
        ]);

        // Load prices with their associated price lists
        $prices = \App\Models\ProductPrice::where('product_id', $product->id)
            ->with('priceList')
            ->get();

        return view('admin.products.show', compact('product', 'prices'));
    }

    public function create()
    {
        $departments = Department::where('is_active', true)->get();
        $categories = ProductCategory::where('is_active', true)->get();
        $units = MeasurementUnit::all(); // Assuming you have this seeded (Pieces, Kg, Liters, etc.)
        
        return view('admin.products.create', compact('departments', 'categories', 'units'));
    }

    public function scanSearch(Request $request)
    {
        $barcode = $request->input('barcode');
        
        $product = Product::whereHas('barcodes', function($q) use ($barcode) {
            $q->where('barcode', $barcode);
        })->orWhere('sku', $barcode)->first();

        if ($product) {
            return response()->json([
                'found' => true,
                'redirect_url' => route('admin.products.edit', $product->id)
            ]);
        }

        return response()->json(['found' => false], 404);
    }

public function searchComponentsAjax(Request $request)
{
    $term = $request->input('term');
    $departmentId = $request->input('department_id');
    $categoryId = $request->input('category_id');
    $allowsFractional = $request->input('allows_fractional_qty');
    $isActive = $request->input('is_active');
    $excludeId = $request->input('exclude_id');
    
    // Assuming a default store ID or fetching from session/auth user
    $storeId = 1; // Adjust based on your multi-store implementation

    $query = Product::with(['saleUnit', 'department', 'category']);

    if ($term) {
        $query->where(function($q) use ($term) {
            $q->where('name', 'LIKE', "%{$term}%")
              ->orWhere('sku', 'LIKE', "%{$term}%");
        });
    }

    if ($departmentId) {
        $query->where('department_id', $departmentId);
    }

    if ($categoryId) {
        $query->where('category_id', $categoryId);
    }

    if ($allowsFractional !== null && $allowsFractional !== '') {
        $query->where('allows_fractional_qty', $allowsFractional);
    }

    if ($isActive !== null && $isActive !== '') {
        $query->where('is_active', $isActive);
    }

    if ($excludeId) {
        $query->where('id', '!=', $excludeId);
    }

    $products = $query->limit(15)->get()->map(function ($product) use ($storeId) {
        // Fetch sale price for the product from the store's price list
        $price = DB::table('product_prices')
            ->join('price_lists', 'product_prices.price_list_id', '=', 'price_lists.id')
            ->where('product_prices.product_id', $product->id)
            ->where('price_lists.store_id', $storeId)
            ->value('product_prices.sale_price') ?? 0.00;

        $unitCode = $product->saleUnit ? $product->saleUnit->code : 'kilogram';
        $unitSymbol = __("messages.measurement_units.{$unitCode}.symbol") ?? $unitCode;

        return [
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'price' => (float) $price,
            'unit' => $unitSymbol,
        ];
    });

    return response()->json($products);
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'category_id' => 'nullable|exists:product_categories,id',
            'inventory_unit_id' => 'required|exists:measurement_units,id',
            'sale_unit_id' => 'required|exists:measurement_units,id',
            'sku' => 'nullable|string|max:50|unique:products,sku',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'product_type' => 'required|string|max:50',
            'primary_barcode' => 'nullable|string|max:50|unique:product_barcodes,barcode',
            'allows_fractional_qty' => 'boolean',
            'track_inventory' => 'boolean',
            'is_active' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $business = Business::first();
            $validated['business_id'] = $business->id;
            $validated['created_by_user_id'] = auth()->id();
            $validated['is_active'] = $request->has('is_active');
            $validated['allows_fractional_qty'] = $request->has('allows_fractional_qty');
            $validated['track_inventory'] = $request->has('track_inventory');
            $validated['sale_to_inventory_factor'] = 1; // Default
            
            // Generate SKU if empty
            if (empty($validated['sku'])) {
                $validated['sku'] = strtoupper(uniqid('PRD-'));
            }

            $product = Product::create($validated);

            // Register the primary barcode if provided (via scanner or generator)
            if (!empty($validated['primary_barcode'])) {
                ProductBarcode::create([
                    'product_id' => $product->id,
                    'unit_id' => $validated['sale_unit_id'],
                    'barcode' => $validated['primary_barcode'],
                    'conversion_factor' => 1,
                    'is_primary' => true,
                ]);
            }

            DB::commit();

            // Fetch the sale unit safely
            $saleUnit = MeasurementUnit::find($validated['sale_unit_id']);
            $unitCode = $saleUnit ? strtolower(trim($saleUnit->code)) : '';

            // Handle Box redirection
            if ($unitCode === 'box') {
                return redirect()->route('admin.products.box.index', $product->id)
                                 ->with('success', 'Producto creado. Ahora configura el contenido de la caja.');
            } 
            
            // Handle Package redirection (matches 'package' from your seeder)
            if ($unitCode === 'package' || $unitCode === 'paquete' || $unitCode === 'pkg') {
                return redirect()->route('admin.products.package.index', $product->id)
                                 ->with('success', 'Producto creado. Ahora configura el contenido del paquete.');
            }

            return redirect()->route('admin.products.pricing.index', $product->id)
                             ->with('success', 'Producto creado correctamente. Por favor, configure los precios.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Se produjo un error al guardar el producto.']);
        }
    }

    public function edit(Product $product)
    {
        $departments = Department::where('is_active', true)->get();
        $categories = ProductCategory::where('is_active', true)->get();
        $units = MeasurementUnit::all();
        
        // Eager load relations for the modular cards
        $product->load(['barcodes', 'images', 'suppliers', 'saleUnit', 'components']);
        
        return view('admin.products.edit', compact('product', 'departments', 'categories', 'units'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'category_id' => 'nullable|exists:product_categories,id',
            'inventory_unit_id' => 'required|exists:measurement_units,id',
            'sale_unit_id' => 'required|exists:measurement_units,id',
            'sku' => 'required|string|max:50|unique:products,sku,' . $product->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'product_type' => 'required|string|max:50',
            'allows_fractional_qty' => 'boolean',
            'track_inventory' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['allows_fractional_qty'] = $request->has('allows_fractional_qty');
        $validated['track_inventory'] = $request->has('track_inventory');
        $validated['updated_by_user_id'] = auth()->id();

        $product->update($validated);

        return redirect()->route('admin.products.edit', $product->id)
                         ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')
                         ->with('success', 'Producto eliminado correctamente.');
    }
}