<?php

namespace App\Http\Controllers;

use App\Models\CashShift;
use App\Models\PosTerminal;
use App\Http\Requests\OpenShiftRequest;
use App\Http\Requests\CloseShiftRequest;
use App\Services\OpenShiftService;
use App\Services\CloseShiftService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class CashShiftController extends Controller
{
    public function __construct(
        private OpenShiftService $openShiftService,
        private CloseShiftService $closeShiftService
    ) {}

    public function open(OpenShiftRequest $request): JsonResponse
    {
        $terminal = PosTerminal::findOrFail($request->validated('terminal_id'));
        
        $shift = $this->openShiftService->execute(
            terminal: $terminal,
            openingCash: (float) $request->validated('opening_cash'),
            userId: $request->user()->id,
            notes: $request->validated('notes')
        );

        return response()->json([
            'message' => 'Shift opened successfully.',
            'data' => $shift
        ], 201);
    }

    public function close(CloseShiftRequest $request, CashShift $shift): JsonResponse
    {
        $closedShift = $this->closeShiftService->execute(
            shift: $shift,
            countLines: $request->validated('count_lines'),
            userId: $request->user()->id,
            notes: $request->validated('notes')
        );

        return response()->json([
            'message' => 'Shift closed successfully.',
            'data' => $closedShift
        ]);
    }

    public function reopen(Request $request, CashShift $shift): JsonResponse
    {
        if ($shift->status !== 'closed') {
            return response()->json(['message' => 'Only closed shifts can be reopened.'], 422);
        }

        $shift->update([
            'status' => 'open',
            'closed_by_user_id' => null,
            'closed_at' => null,
        ]);

        return response()->json([
            'message' => 'Shift reopened successfully.',
            'data' => $shift
        ]);
    }

    // For Admin Web Dashboard (Returns HTML Blade View)
    public function showSummary(CashShift $shift)
    {
        $shift->load(['opener', 'closer', 'terminal', 'store', 'countLines.denomination']);
        
        return view('admin.shifts.summary', compact('shift'));
    }

    public function summary(CashShift $shift): JsonResponse
    {
        $totalSales = DB::table('sales')
            ->where('shift_id', $shift->id)
            ->where('status', 'completed')
            ->sum('total');

        $transactionCount = DB::table('sales')
            ->where('shift_id', $shift->id)
            ->where('status', 'completed')
            ->count();

        // Top Sold Products
        $topProducts = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->where('sales.shift_id', $shift->id)
            ->where('sales.status', 'completed')
            ->select('products.name', DB::raw('SUM(sale_items.quantity) as total_qty'), DB::raw('SUM(sale_items.line_total) as total_revenue'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // Payments Breakdown by Method
        $paymentsBreakdown = DB::table('sale_payments')
            ->join('sales', 'sale_payments.sale_id', '=', 'sales.id')
            ->join('payment_methods', 'sale_payments.payment_method_id', '=', 'payment_methods.id')
            ->where('sales.shift_id', $shift->id)
            ->where('sales.status', 'completed')
            ->select('payment_methods.name', DB::raw('SUM(sale_payments.amount) as total'))
            ->groupBy('payment_methods.id', 'payment_methods.name')
            ->get();

        return response()->json([
            'shift' => $shift->load(['opener', 'closer', 'terminal', 'store']),
            'total_sales' => (float) $totalSales,
            'transaction_count' => $transactionCount,
            'top_products' => $topProducts,
            'payments_breakdown' => $paymentsBreakdown,
            'denominations' => DB::table('cash_denominations')->where('is_active', true)->get()
        ]);
    }

    public function activeSummary(Request $request): JsonResponse
    {
        // Find the most recent open shift for the authenticated user or terminal
        $shift = CashShift::where('status', 'open')
            ->where(function($query) use ($request) {
                $query->where('opened_by_user_id', $request->user()->id);
                if ($request->has('terminal_id')) {
                    $query->orWhere('terminal_id', $request->input('terminal_id'));
                }
            })
            ->latest('opened_at')
            ->first();

        if (!$shift) {
            return response()->json([
                'shift' => null,
                'denominations' => DB::table('cash_denominations')->where('is_active', true)->get()
            ]);
        }

        // Delegate to the existing summary logic using the found active shift ID
        return $this->summary($shift);
    }
}