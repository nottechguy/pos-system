<?php

namespace App\Services;

use App\Models\CashShift;
use App\Models\CashCountLine;
use App\Models\CashDenomination;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;

class CloseShiftService
{
    public function execute(CashShift $shift, array $countLines, int $userId, ?string $notes = null): CashShift
    {
        return DB::transaction(function () use ($shift, $countLines, $userId, $notes) {
            
            // 1. Lock the open shift
            $shift = CashShift::where('id', $shift->id)->lockForUpdate()->first();

            // 2. Calculate expected cash from the immutable movements ledger
            $expectedCash = $shift->cashMovements()->sum('amount');
            
            // 3. Process counted cash (Denominations)
            $countedCash = 0;
            foreach ($countLines as $line) {
                $denomination = CashDenomination::find($line['denomination_id']);
                $lineTotal = $denomination->value * $line['quantity'];
                $countedCash += $lineTotal;

                CashCountLine::create([
                    'cash_shift_id' => $shift->id,
                    'denomination_id' => $denomination->id,
                    'quantity' => $line['quantity'],
                    'total' => $lineTotal,
                ]);
            }

            // 4. Calculate the difference
            $difference = $countedCash - $expectedCash;

            $oldValues = $shift->toArray();

            // 5. Close the shift
            $shift->update([
                'status' => 'closed',
                'closed_by_user_id' => $userId,
                'expected_cash' => $expectedCash,
                'counted_cash' => $countedCash,
                'difference' => $difference,
                'closed_at' => now(),
                'notes' => $notes ? $shift->notes . "\nClosing Note: " . $notes : $shift->notes,
            ]);

            // 6. Record Audit Log
            AuditLog::create([
                'user_id' => $userId,
                'store_id' => $shift->store_id,
                'terminal_id' => $shift->terminal_id,
                'action' => 'shift.closed',
                'auditable_type' => CashShift::class,
                'auditable_id' => $shift->id,
                'old_values' => $oldValues,
                'new_values' => $shift->toArray(),
                'created_at' => now(),
            ]);

            return $shift;
        });
    }
}