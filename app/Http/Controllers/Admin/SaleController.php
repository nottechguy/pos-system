<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Http\Request;
use App\Jobs\PrintSaleReceiptJob;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with(['cashier', 'store'])->orderBy('sold_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('number', 'like', "%{$search}%");
        }

        if ($request->filled('date_from')) {
            $query->whereDate('sold_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('sold_at', '<=', $request->date_to);
        }

        $sales = $query->paginate(20)->withQueryString();

        return view('admin.sales.index', compact('sales'));
    }

    public function show(Sale $sale)
    {
        // Eager load everything needed to render the snapshot correctly
        $sale->load([
            'items.discounts', 
            'items.product.prices', // Eager load product prices for fallback cost lookup
            'payments.paymentMethod', 
            'cashier', 
            'store'
        ]);
        
        // Calculate Cost of Goods Sold (COGS) and Net Profit for this sale
        $totalCost = 0;
        foreach ($sale->items as $item) {
            $costPerUnit = (float) $item->unit_cost_snapshot;
            
            // Fallback if snapshot was recorded as 0
            if ($costPerUnit <= 0 && $item->product) {
                $costPerUnit = (float) ($item->product->prices->where('cost_price', '>', 0)->first()?->cost_price ?? 0);
            }

            $totalCost += $costPerUnit * (float) $item->quantity;
        }

        $grossRevenue = (float) $sale->total;
        $netProfit = $grossRevenue - $totalCost;
        $profitMargin = $grossRevenue > 0 ? ($netProfit / $grossRevenue) * 100 : 0;

        return view('admin.sales.show', compact('sale', 'totalCost', 'netProfit', 'profitMargin'));
    }

    public function print(Request $request, Sale $sale)
    {
        $validated = $request->validate([
            'mode' => 'nullable|string|in:Ticket,Nota de Venta'
        ]);

        $mode = $validated['mode'] ?? 'Ticket';
        
        try {
            PrintSaleReceiptJob::dispatchSync($sale->id, $mode);
            return redirect()->back()->with('success', "Ticket enviado a la impresora ({$mode}).");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', "Error al imprimir: " . $e->getMessage());
        }
    }
}