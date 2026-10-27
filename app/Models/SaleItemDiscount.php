<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItemDiscount extends Model
{
    // Discounts on immutable sale documents do not update
    public const UPDATED_AT = null;

    protected $fillable = [
        'sale_item_id',
        'quantity_rule_id',
        'discount_type',
        'description',
        'rate',
        'amount',
    ];

    protected function casts(): array
    {
        return [
            'rate' => 'decimal:4',
            'amount' => 'decimal:2',
        ];
    }

    public function saleItem(): BelongsTo
    {
        return $this->belongsTo(SaleItem::class);
    }

    public function quantityRule(): BelongsTo
    {
        return $this->belongsTo(ProductQuantityRule::class);
    }
}