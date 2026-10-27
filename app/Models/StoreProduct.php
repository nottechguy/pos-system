<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreProduct extends Model
{
    // Disable default primary key behavior for composite keys
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'store_id',
        'product_id',
        'preferred_supplier_id',
        'default_cost',
        'last_cost',
        'average_cost',
        'minimum_quantity',
        'maximum_quantity',
        'reorder_quantity',
        'shelf_location',
        'is_available',
    ];

    protected function casts(): array
    {
        return [
            'default_cost' => 'decimal:4',
            'last_cost' => 'decimal:4',
            'average_cost' => 'decimal:4',
            'minimum_quantity' => 'decimal:4',
            'maximum_quantity' => 'decimal:4',
            'reorder_quantity' => 'decimal:4',
            'is_available' => 'boolean',
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

    public function preferredSupplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'preferred_supplier_id');
    }
}