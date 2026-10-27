<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryBalance extends Model
{
    // Disable default primary key behavior for composite keys
    protected $primaryKey = null;
    public $incrementing = false;
    
    // We only use updated_at for optimistic locking tracking
    public const CREATED_AT = null;

    protected $fillable = [
        'store_id',
        'product_id',
        'quantity_on_hand',
        'quantity_reserved',
        'version',
    ];

    protected function casts(): array
    {
        return [
            'quantity_on_hand' => 'decimal:4',
            'quantity_reserved' => 'decimal:4',
            'version' => 'integer',
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

    /**
     * Dynamically calculates the actual available quantity
     */
    public function getQuantityAvailableAttribute(): float
    {
        return (float) $this->quantity_on_hand - (float) $this->quantity_reserved;
    }
}