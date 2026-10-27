<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuspendedSaleItem extends Model
{
    protected $fillable = [
        'suspended_sale_id',
        'product_id',
        'price_list_id',
        'quantity',
        'unit_price',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:4',
            'unit_price' => 'decimal:4',
            'metadata' => 'array', // JSON cast
        ];
    }

    public function suspendedSale(): BelongsTo
    {
        return $this->belongsTo(SuspendedSale::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function priceList(): BelongsTo
    {
        return $this->belongsTo(PriceList::class);
    }
}