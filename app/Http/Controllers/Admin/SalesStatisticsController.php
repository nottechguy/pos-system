<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SalesStatisticsService;
use App\Models\User;
use Illuminate\Http\Request;

class SalesStatisticsController extends Controller
{
    public function __construct(private SalesStatisticsService $statsService) {}

    public function index(Request $request)
    {
        $filters = [
            'period' => $request->input('period', 'all'), // Default to 'all' history
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
            'cashier_id' => $request->input('cashier_id'),
        ];

        $stats = $this->statsService->getStats($filters);
        $cashiers = User::whereHas('sales')->get();

        return view('admin.sales.statistics', compact('stats', 'cashiers', 'filters'));
    }
}