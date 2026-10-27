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
            'items.product', 
            'payments.paymentMethod', 
            'cashier', 
            'store'
        ]);
        
        return view('admin.sales.show', compact('sale'));
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