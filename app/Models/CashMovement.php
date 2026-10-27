<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CashMovement extends Model
{
    // The cash ledger is strictly immutable
    public const UPDATED_AT = null;

    protected $fillable = [
        'cash_shift_id',
        'user_id',
        'movement_type',
        'amount',
        'reference_type',
        'reference_id',
        'reason',
        'occurred_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'occurred_at' => 'datetime',
        ];
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(CashShift::class, 'cash_shift_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Resolves the polymorphic document that triggered the cash movement
     * (e.g., SalePayment, SaleReturnPayment, or null for manual drawer pops)
     */
    public function reference(): MorphTo
    {
        return $this->morphTo();
    }
}