<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\PriceList;
use App\Models\Business;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::with('priceList');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('business_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->paginate(50)->withQueryString();
        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        $priceLists = PriceList::where('is_active', true)->get();
        return view('admin.customers.create', compact('priceLists'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_type' => 'required|in:individual,business',
            'first_name' => 'required_if:customer_type,individual|nullable|string|max:100',
            'last_name' => 'required_if:customer_type,individual|nullable|string|max:100',
            'business_name' => 'required_if:customer_type,business|nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'tax_id' => 'nullable|string|max:50',
            'price_list_id' => 'nullable|exists:price_lists,id',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $business = Business::first();
        $validated['business_id'] = $business->id;
        $validated['is_active'] = $request->has('is_active');
        $validated['code'] = strtoupper(uniqid('CUS-'));

        Customer::create($validated);

        return redirect()->route('admin.customers.index')->with('success', 'Cliente creado con éxito.');
    }

    public function edit(Customer $customer)
    {
        $priceLists = PriceList::where('is_active', true)->get();
        $departments = \App\Models\Department::where('is_active', true)->get();
        $categories = \App\Models\ProductCategory::where('is_active', true)->get();
        
        $customer->load('customPrices.product');

        return view('admin.customers.edit', compact('customer', 'priceLists', 'departments', 'categories'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'customer_type' => 'required|in:individual,business',
            'first_name' => 'required_if:customer_type,individual|nullable|string|max:100',
            'last_name' => 'required_if:customer_type,individual|nullable|string|max:100',
            'business_name' => 'required_if:customer_type,business|nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'tax_id' => 'nullable|string|max:50',
            'price_list_id' => 'nullable|exists:price_lists,id',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Clear irrelevant fields based on type
        if ($validated['customer_type'] === 'business') {
            $validated['first_name'] = null;
            $validated['last_name'] = null;
        } else {
            $validated['business_name'] = null;
        }

        $customer->update($validated);

        return redirect()->route('admin.customers.index')->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('admin.customers.index')->with('success', 'Cliente eliminado con éxito.');
    }

    public function storeCustomPrice(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Format data for syncing/upserting custom prices
            $syncData = [];
            foreach ($request->products as $prodData) {
                $syncData[$prodData['product_id']] = [
                    'price' => $prodData['price']
                ];
            }

            // Using syncWithoutDetaching or sync depending on whether you want to replace all or merge. 
            // sync() will replace the list with exactly what's submitted.
            $customer->customPrices()->delete(); // Clear old ones or use sync on a relation if configured as BelongsToMany. 
            // Since customPrices() is a HasMany to CustomerProductPrice, we can loop or delete/create:
            foreach ($syncData as $productId => $data) {
                $customer->customPrices()->updateOrCreate(
                    ['product_id' => $productId],
                    ['price' => $data['price']]
                );
            }

            DB::commit();
            return redirect()->route('admin.customers.edit', $customer->id)
                             ->with('success', 'Precios personalizados guardados correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Error al guardar los precios: ' . $e->getMessage()]);
        }
    }

    public function destroyCustomPrice(Customer $customer, $customPriceId)
    {
        $customer->customPrices()->where('id', $customPriceId)->delete();

        return redirect()->route('admin.customers.edit', $customer->id)
                         ->with('success', 'Precio personalizado eliminado correctamente.');
    }
}