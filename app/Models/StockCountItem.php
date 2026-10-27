<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockCountItem extends Model
{
    protected $fillable = [
        'stock_count_id',
        'product_id',
        'expected_quantity',
        'counted_quantity',
        'unit_cost_snapshot',
        'counted_by_user_id',
        'counted_at',
    ];

    protected function casts(): array
    {
        return [
            'expected_quantity' => 'decimal:4',
            'counted_quantity' => 'decimal:4',
            'unit_cost_snapshot' => 'decimal:4',
            'counted_at' => 'datetime',
        ];
    }

    public function stockCount(): BelongsTo
    {
        return $this->belongsTo(StockCount::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function counter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'counted_by_user_id');
    }

    /**
     * Dynamically calculates the physical inventory variance.
     * Returns null if the item hasn't been physically counted yet.
     */
    public function getVarianceAttribute(): ?float
    {
        if (is_null($this->counted_quantity)) {
            return null;
        }

        return (float) $this->counted_quantity - (float) $this->expected_quantity;
    }
}