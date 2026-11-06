<?php

namespace App\Services;

use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesStatisticsService
{
    public function getStats(array $filters = []): array
    {
        $query = Sale::with(['items.product.prices', 'cashier', 'payments.paymentMethod']);

        // Include common successful statuses
        $query->whereIn('status', ['completed', 'paid', 'closed']);

        // 1. Explicit Custom Date Range takes precedence
        if (!empty($filters['date_from']) || !empty($filters['date_to'])) {
            if (!empty($filters['date_from'])) {
                $query->whereDate('sold_at', '>=', $filters['date_from']);
            }
            if (!empty($filters['date_to'])) {
                $query->whereDate('sold_at', '<=', $filters['date_to']);
            }
        } 
        // 2. Quick Period Filter (only applied if no custom dates are specified)
        elseif (!empty($filters['period']) && $filters['period'] !== 'all') {
            switch ($filters['period']) {
                case 'day':
                    $query->whereDate('sold_at', Carbon::today());
                    break;
                case 'week':
                    $query->whereBetween('sold_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('sold_at', Carbon::now()->month)
                          ->whereYear('sold_at', Carbon::now()->year);
                    break;
                case 'year':
                    $query->whereYear('sold_at', Carbon::now()->year);
                    break;
            }
        }

        // Cashier Filter
        if (!empty($filters['cashier_id'])) {
            $query->where('cashier_user_id', $filters['cashier_id']);
        }

        $sales = $query->get();

        // Calculations
        $totalSalesCount = $sales->count();
        $grossRevenue = $sales->sum('total');
        $totalDiscount = $sales->sum('discount_total');
        $totalTax = $sales->sum('tax_total');

        $totalCost = 0;
        $productSalesMap = [];

        foreach ($sales as $sale) {
            foreach ($sale->items as $item) {
                // Uses the effective_unit_cost accessor to fallback if snapshot was 0
                $itemCost = (float) $item->effective_unit_cost * (float) $item->quantity;
                $totalCost += $itemCost;

                $prodId = $item->product_id ?? 'unknown';
                $prodName = $item->name_snapshot ?? 'Producto Desconocido';

                if (!isset($productSalesMap[$prodId])) {
                    $productSalesMap[$prodId] = [
                        'name' => $prodName,
                        'quantity' => 0,
                        'revenue' => 0,
                    ];
                }
                $productSalesMap[$prodId]['quantity'] += (float) $item->quantity;
                $productSalesMap[$prodId]['revenue'] += (float) $item->line_total;
            }
        }

        $netProfit = $grossRevenue - $totalCost;
        $profitMargin = $grossRevenue > 0 ? ($netProfit / $grossRevenue) * 100 : 0;

        usort($productSalesMap, function ($a, $b) {
            return $b['quantity'] <=> $a['quantity'];
        });
        $topProducts = array_slice($productSalesMap, 0, 5);

        // Payment Methods Breakdown
        $paymentQuery = DB::table('sale_payments')
            ->join('sales', 'sale_payments.sale_id', '=', 'sales.id')
            ->join('payment_methods', 'sale_payments.payment_method_id', '=', 'payment_methods.id')
            ->whereIn('sales.status', ['completed', 'paid', 'closed']);

        if (!empty($filters['date_from'])) {
            $paymentQuery->whereDate('sales.sold_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $paymentQuery->whereDate('sales.sold_at', '<=', $filters['date_to']);
        } elseif (empty($filters['date_from']) && !empty($filters['period']) && $filters['period'] !== 'all') {
            if ($filters['period'] === 'day') {
                $paymentQuery->whereDate('sales.sold_at', Carbon::today());
            } elseif ($filters['period'] === 'week') {
                $paymentQuery->whereBetween('sales.sold_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            } elseif ($filters['period'] === 'month') {
                $paymentQuery->whereMonth('sales.sold_at', Carbon::now()->month)->whereYear('sales.sold_at', Carbon::now()->year);
            } elseif ($filters['period'] === 'year') {
                $paymentQuery->whereYear('sales.sold_at', Carbon::now()->year);
            }
        }

        if (!empty($filters['cashier_id'])) {
            $paymentQuery->where('sales.cashier_user_id', $filters['cashier_id']);
        }

        $paymentBreakdown = $paymentQuery
            ->select('payment_methods.code', DB::raw('SUM(sale_payments.amount) as total'))
            ->groupBy('payment_methods.id', 'payment_methods.code')
            ->get();

        return [
            'total_sales_count' => $totalSalesCount,
            'gross_revenue' => (float) $grossRevenue,
            'total_cost' => (float) $totalCost,
            'net_profit' => (float) $netProfit,
            'profit_margin' => (float) $profitMargin,
            'total_discount' => (float) $totalDiscount,
            'total_tax' => (float) $totalTax,
            'top_products' => $topProducts,
            'payment_breakdown' => $paymentBreakdown,
        ];
    }
}