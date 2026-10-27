<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Promotion;
use App\Models\Product;
use App\Models\PriceList;
use App\Models\Business;
use App\Services\PromotionNotificationService;
use Illuminate\Support\Facades\DB;

class PromotionController extends Controller
{
    public function index(Request $request)
    {
        $query = Promotion::with(['products', 'creator']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $promotions = $query->paginate(30)->withQueryString();
        return view('admin.promotions.index', compact('promotions'));
    }

    public function show($id)
    {
        $promotion = Promotion::with(['products' => function($q) {
            $q->withPivot('promotional_price', 'discount_value');
        }, 'creator'])->findOrFail($id);

        return view('admin.promotions.show', compact('promotion'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)->get();
        $departments = \App\Models\Department::where('is_active', true)->get();
        $categories = \App\Models\ProductCategory::where('is_active', true)->get();

        return view('admin.promotions.create', compact('products', 'departments', 'categories'));
    }

    public function store(Request $request, PromotionNotificationService $notificationService)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'promotion_type' => 'required|string|in:single,pack,bulk',
            'promotional_price' => 'nullable|numeric|min:0',
            'minimum_quantity' => 'required|numeric|min:0.01',
            'rule_type' => 'nullable|string',
            'discount_value' => 'nullable|numeric|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.promotional_price' => 'nullable|numeric|min:0',
            'products.*.discount_value' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $business = Business::first();
            $validated['business_id'] = $business->id;
            $validated['created_by_user_id'] = auth()->id();
            $validated['is_active'] = $request->has('is_active');
            $validated['promotional_price'] = $validated['promotional_price'] ?? 0;

            if ($request->hasFile('banner_image')) {
                $validated['banner_image'] = $request->file('banner_image')->store('promotions', 'public');
            }

            $promotion = Promotion::create($validated);

            // Format sync data for the pivot table
            $syncData = [];
            foreach ($request->products as $prodData) {
                $syncData[$prodData['product_id']] = [
                    'promotional_price' => $prodData['promotional_price'] ?? null,
                    'discount_value' => $prodData['discount_value'] ?? null,
                ];
            }
            $promotion->products()->sync($syncData);

            // Trigger FCM / Notification Service
            $notificationService->sendPromotionNotification($promotion, 'created');

            DB::commit();
            return redirect()->route('admin.promotions.index')->with('success', 'Promoción creada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Removed dd() so error redirection works properly
            return back()->withInput()->withErrors(['error' => 'Error al guardar: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $promotion = Promotion::with(['products' => function($q) {
            $q->withPivot('promotional_price', 'discount_value');
        }])->findOrFail($id);
        
        $departments = \App\Models\Department::all();
        $categories = \App\Models\ProductCategory::all();

        return view('admin.promotions.edit', compact('promotion', 'departments', 'categories'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'promotion_type' => 'required|string|in:single,pack,bulk',
            'promotional_price' => 'nullable|numeric|min:0',
            'minimum_quantity' => 'required|numeric|min:0.01',
            'rule_type' => 'nullable|string',
            'discount_value' => 'nullable|numeric|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.promotional_price' => 'nullable|numeric|min:0',
            'products.*.discount_value' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $validated['is_active'] = $request->has('is_active');
            $validated['promotional_price'] = $validated['promotional_price'] ?? 0;

            if ($request->hasFile('banner_image')) {
                $validated['banner_image'] = $request->file('banner_image')->store('promotions', 'public');
            }

            $promotion->update($validated);

            // Format sync data for the pivot table with individual overrides
            $syncData = [];
            foreach ($request->products as $prodData) {
                $syncData[$prodData['product_id']] = [
                    'promotional_price' => $prodData['promotional_price'] ?? null,
                    'discount_value' => $prodData['discount_value'] ?? null,
                ];
            }
            $promotion->products()->sync($syncData);

            DB::commit();
            return redirect()->route('admin.promotions.index')->with('success', 'Promoción actualizada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Error al actualizar: ' . $e->getMessage()]);
        }
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();
        return redirect()->route('admin.promotions.index')->with('success', 'Promoción eliminada.');
    }
}