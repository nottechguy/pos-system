<?php

namespace App\Services;

use App\Models\CashShift;
use App\Models\PosTerminal;
use App\Models\CashMovement;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class OpenShiftService {
    public function execute(PosTerminal $terminal, float $openingCash, int $userId, ?string $notes = null): CashShift {
        return DB::transaction(function () use ($terminal, $openingCash, $userId, $notes) {
            
            // 1. Lock the terminal to prevent concurrent shift openings
            $terminal = PosTerminal::where('id', $terminal->id)->lockForUpdate()->first();

            if (CashShift::where('terminal_id', $terminal->id)->where('status', 'open')->exists()) {
                throw new Exception('This terminal already has an open shift.');
            }

            // 2. Create the shift
            $shift = CashShift::create([
                'uuid' => Str::uuid(),
                'store_id' => $terminal->store_id,
                'terminal_id' => $terminal->id,
                'opened_by_user_id' => $userId,
                'status' => 'open',
                'opening_cash' => $openingCash,
                'opened_at' => now(),
                'notes' => $notes,
            ]);

            // 3. Register the initial cash movement (immutable ledger)
            if ($openingCash > 0) {
                CashMovement::create([
                    'cash_shift_id' => $shift->id,
                    'user_id' => $userId,
                    'movement_type' => 'opening',
                    'amount' => $openingCash,
                    'reference_type' => CashShift::class,
                    'reference_id' => $shift->id,
                    'reason' => 'Initial float',
                    'occurred_at' => now(),
                ]);
            }

            // 4. Record Immutable Audit Log
            AuditLog::create([
                'user_id' => $userId,
                'store_id' => $terminal->store_id,
                'terminal_id' => $terminal->id,
                'action' => 'shift.opened',
                'auditable_type' => CashShift::class,
                'auditable_id' => $shift->id,
                'new_values' => $shift->toArray(),
                'created_at' => now(),
            ]);

            return $shift;
        });
    }
}