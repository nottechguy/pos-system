<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CashShift;
use App\Models\PosTerminal;

class CashShiftPolicy
{
    /**
     * Determine whether the user can open a shift on the terminal.
     */
    public function open(User $user, PosTerminal $terminal): bool
    {
        // Must have permission AND be actively assigned to the terminal's store
        return $user->can('shifts.open') && 
               $user->stores()->where('stores.id', $terminal->store_id)->exists();
    }

    /**
     * Determine whether the user can close the shift.
     */
    public function close(User $user, CashShift $shift): bool
    {
        return $user->can('shifts.close') && 
               $user->stores()->where('stores.id', $shift->store_id)->exists();
    }

    /**
     * Determine whether the user can view the shift details.
     */
    public function view(User $user, CashShift $shift): bool
    {
        return $user->can('reports.view') && 
               $user->stores()->where('stores.id', $shift->store_id)->exists();
    }
}