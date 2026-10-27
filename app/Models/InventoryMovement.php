<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class InventoryMovement extends Model
{
    // The ledger is strictly immutable. Disable updated_at.
    public const UPDATED_AT = null;

    protected $fillable = [
        'uuid',
        'store_id',
        'product_id',
        'user_id',
        'shift_id',
        'movement_type',
        'quantity',
        'unit_cost',
        'balance_after',
        'reference_type',
        'reference_id',
        'reason',
        'occurred_at',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:4',
            'unit_cost' => 'decimal:4',
            'balance_after' => 'decimal:4',
            'occurred_at' => 'datetime',
        ];
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(CashShift::class);
    }

    /**
     * Resolves the polymorphic document that triggered the movement
     */
    public function reference(): MorphTo
    {
        return $this->morphTo();
    }
}