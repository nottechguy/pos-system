<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InventoryBalance;
use App\Models\InventoryMovement;
use App\Models\Store;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $storeId = $request->input('store_id', Store::first()?->id);
        $stores = Store::where('is_active', true)->get();

        $query = InventoryBalance::with(['product.saleUnit', 'store'])
            ->where('store_id', $storeId);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('product', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Filter for low stock items where quantity_on_hand <= minimum_quantity configured in store_products
        if ($request->boolean('low_stock')) {
            $query->whereRaw('quantity_on_hand <= (
                SELECT minimum_quantity FROM store_products 
                WHERE store_products.store_id = inventory_balances.store_id 
                AND store_products.product_id = inventory_balances.product_id
            )');
        }

        $balances = $query->paginate(50)->withQueryString();

        return view('admin.inventory.index', compact('balances', 'stores', 'storeId'));
    }

    public function movements(Request $request)
    {
        $query = InventoryMovement::with(['product', 'store', 'user', 'shift'])
            ->latest('occurred_at');

        if ($request->filled('store_id')) {
            $query->where('store_id', $request->store_id);
        }

        if ($request->filled('movement_type')) {
            $query->where('movement_type', $request->movement_type);
        }

        $movements = $query->paginate(50)->withQueryString();
        $stores = Store::where('is_active', true)->get();

        return view('admin.inventory.movements', compact('movements', 'stores'));
    }

    public function createStock(Product $product, Request $request)
    {
        $stores = Store::where('is_active', true)->get();
        return view('admin.inventory.stock-create', compact('product', 'stores'));
    }

    public function storeStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'quantity' => 'required|numeric',
            'reason' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($validated, $product) {
            $balance = InventoryBalance::firstOrCreate(
                ['store_id' => $validated['store_id'], 'product_id' => $product->id],
                ['quantity_on_hand' => 0, 'quantity_reserved' => 0]
            );

            $oldQty = $balance->quantity_on_hand;
            $newQty = $validated['quantity'];

            $balance->update(['quantity_on_hand' => $newQty]);

            // Record movement ledger
            InventoryMovement::create([
                'uuid' => \Illuminate\Support\Str::uuid(),
                'store_id' => $validated['store_id'],
                'product_id' => $product->id,
                'user_id' => auth()->id(),
                'movement_type' => 'adjustment',
                'quantity' => $newQty - $oldQty,
                'balance_after' => $newQty,
                'reason' => $validated['reason'] ?? 'Initial stock setup after product creation',
                'occurred_at' => now(),
            ]);

            // Check Low Stock Threshold & Trigger FCM Placeholder
            $this->checkLowStockAndNotify($product, $validated['store_id'], $newQty);
        });

        return redirect()->route('admin.products.index')
                         ->with('success', 'Saldo inicial de inventario configurado correctamente.');
    }

    protected function checkLowStockAndNotify(Product $product, int $storeId, float $currentStock)
    {
        // Placeholder check against store minimum quantity settings
        $isLowStock = $currentStock <= 5; // Example threshold check

        if ($isLowStock) {
            // TODO: Integrate Firebase Cloud Messaging (FCM) notification dispatch here
            // Example:
            // \App\Services\FcmNotificationService::sendToAdmins([
            //     'title' => 'Stock Bajo Alerta',
            //     'body' => "El producto {$product->name} tiene existencias bajas ({$currentStock})."
            // ]);
        }
    }
}