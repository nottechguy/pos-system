<?php

namespace App\Services;

use App\Models\CashShift;
use App\Models\CashCountLine;
use App\Models\CashDenomination;
use App\Models\CashMovement;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use Exception;

class CloseShiftService
{
    public function execute(CashShift $shift, array $countLines, int $userId, ?string $notes = null): CashShift
    {
        return DB::transaction(function () use ($shift, $countLines, $userId, $notes) {
            $shift = CashShift::where('id', $shift->id)->lockForUpdate()->first();

            if ($shift->status !== 'open') {
                throw new Exception('This shift is already closed.');
            }

            // 1. Calculate Total Counted Cash & Save Count Lines securely using DB values
            $countedCash = 0;
            $shift->countLines()->delete();

            foreach ($countLines as $line) {
                $quantity = (int) ($line['quantity'] ?? 0);
                if ($quantity <= 0) {
                    continue;
                }

                $denominationId = $line['denomination_id'] ?? null;
                $denomination = CashDenomination::find($denominationId);

                if (!$denomination) {
                    continue;
                }

                $lineTotal = (float) $quantity * (float) $denomination->value;
                $countedCash += $lineTotal;

                CashCountLine::create([
                    'cash_shift_id' => $shift->id,
                    'denomination_id' => $denominationId,
                    'quantity' => $quantity,
                    'total' => $lineTotal,
                ]);
            }

            // 2. Calculate Expected Cash (Opening Cash + Cash Sales + Cash Movements)
            $cashMovementsTotal = CashMovement::where('cash_shift_id', $shift->id)
                ->where('movement_type', '!=', 'opening')
                ->sum('amount');

            $cashSalesTotal = DB::table('sale_payments')
                ->join('sales', 'sale_payments.sale_id', '=', 'sales.id')
                ->join('payment_methods', 'sale_payments.payment_method_id', '=', 'payment_methods.id')
                ->where('sales.shift_id', $shift->id)
                ->where('payment_methods.code', 'cash')
                ->sum('sale_payments.amount');

            $expectedCash = (float) $shift->opening_cash + (float) $cashSalesTotal + (float) $cashMovementsTotal;
            $difference = $countedCash - $expectedCash;

            // 3. Update Shift
            $shift->update([
                'status' => 'closed',
                'closed_by_user_id' => $userId,
                'expected_cash' => $expectedCash,
                'counted_cash' => $countedCash,
                'difference' => $difference,
                'closed_at' => now(),
                'notes' => $notes,
            ]);

            // 4. Audit Log
            AuditLog::create([
                'user_id' => $userId,
                'store_id' => $shift->store_id,
                'terminal_id' => $shift->terminal_id,
                'action' => 'shift.closed',
                'auditable_type' => CashShift::class,
                'auditable_id' => $shift->id,
                'new_values' => $shift->toArray(),
                'created_at' => now(),
            ]);

            return $shift;
        });
    }
}