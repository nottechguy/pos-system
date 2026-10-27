<?php

namespace App\Http\Controllers;

use App\Models\CashShift;
use App\Models\PosTerminal;
use App\Http\Requests\OpenShiftRequest;
use App\Http\Requests\CloseShiftRequest;
use App\Services\OpenShiftService;
use App\Services\CloseShiftService;
use Illuminate\Http\JsonResponse;
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
        
        Gate::authorize('open', [CashShift::class, $terminal]);

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
        Gate::authorize('close', $shift);

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
}