<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CashShift;
use App\Models\PosTerminal;
use App\Services\OpenShiftService;

class ShiftController extends Controller
{
    public function create()
    {
        // Prevent opening multiple shifts simultaneously
        $activeShift = CashShift::where('opened_by_user_id', auth()->id())
            ->where('status', 'open')
            ->first();

        if ($activeShift) {
            return redirect()->route('admin.pos.index');
        }

        $terminals = PosTerminal::where('is_active', true)->get();

        return view('admin.shifts.create', compact('terminals'));
    }

    public function store(Request $request, OpenShiftService $openShiftService)
    {
        $validated = $request->validate([
            'terminal_id' => 'required|exists:pos_terminals,id',
            'opening_cash' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $terminal = PosTerminal::findOrFail($validated['terminal_id']);

        try {
            $openShiftService->execute(
                terminal: $terminal,
                openingCash: $validated['opening_cash'],
                userId: auth()->id(),
                notes: $validated['notes']
            );

            // Redirect immediately to the POS once opened
            return redirect()->route('admin.pos.index');
            
        } catch (\Exception $e) {
            return back()->withErrors(['terminal_id' => $e->getMessage()])->withInput();
        }
    }
}