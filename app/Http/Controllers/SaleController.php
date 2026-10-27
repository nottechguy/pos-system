<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Http\Requests\CompleteSaleRequest;
use App\Services\CompleteSaleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use App\Jobs\PrintSaleReceiptJob;

class SaleController extends Controller
{
    public function __construct(
        private CompleteSaleService $completeSaleService
    ) {}

    public function store(CompleteSaleRequest $request): JsonResponse
    {
        $store = Store::findOrFail($request->validated('store_id'));
        
        // Ensure user is authorized to sell at this specific store
        Gate::authorize('create', [Sale::class, $store]);

        // Execute transaction
        $sale = $this->completeSaleService->execute(
            data: $request->validated(),
            userId: $request->user()->id
        );

        PrintSaleReceiptJob::dispatch($sale->id);

        return response()->json([
            'message' => 'Sale completed successfully.',
            'data' => $sale
        ], 201);
    }
}