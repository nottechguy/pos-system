<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Business;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('legal_name', 'like', "%{$search}%")
                  ->orWhere('trade_name', 'like', "%{$search}%")
                  ->orWhere('tax_id', 'like', "%{$search}%");
        }

        $suppliers = $query->paginate(30)->withQueryString();
        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('admin.suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'legal_name' => 'required|string|max:160',
            'trade_name' => 'nullable|string|max:160',
            'tax_id' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:160',
            'phone' => 'nullable|string|max:40',
            'supplier_type' => 'required|string|max:30',
            'is_active' => 'boolean',
        ]);

        DB::transaction(function () use ($validated) {
            $business = Business::first();
            $validated['business_id'] = $business->id;
            $validated['code'] = strtoupper(uniqid('SUP-'));
            $validated['is_active'] = request()->has('is_active');

            Supplier::create($validated);
        });

        return redirect()->route('admin.suppliers.index')->with('success', 'Proveedor creado exitosamente.');
    }

    public function edit(Supplier $supplier)
    {
        $supplier->load(['addresses', 'contacts', 'products']);
        return view('admin.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'legal_name' => 'required|string|max:160',
            'trade_name' => 'nullable|string|max:160',
            'tax_id' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:160',
            'phone' => 'nullable|string|max:40',
            'supplier_type' => 'required|string|max:30',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $supplier->update($validated);

        return redirect()->route('admin.suppliers.edit', $supplier->id)->with('success', 'Proveedor actualizado correctamente.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('admin.suppliers.index')->with('success', 'Proveedor eliminado.');
    }
}