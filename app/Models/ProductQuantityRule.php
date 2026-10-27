<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductQuantityRule extends Model
{
    protected $fillable = [
        'product_id',
        'price_list_id',
        'name',
        'rule_type',
        'minimum_quantity',
        'group_quantity',
        'discount_value',
        'repeatable',
        'priority',
        'starts_at',
        'ends_at',
        'is_active',
        'created_by_user_id',
    ];

    protected function casts(): array
    {
        return [
            'minimum_quantity' => 'decimal:4',
            'group_quantity' => 'decimal:4',
            'discount_value' => 'decimal:4',
            'repeatable' => 'boolean',
            'priority' => 'integer',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function priceList(): BelongsTo
    {
        return $this->belongsTo(PriceList::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}