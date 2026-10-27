<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItemTax extends Model
{
    // Taxes on immutable sale documents do not update
    public const UPDATED_AT = null;

    protected $fillable = [
        'sale_item_id',
        'tax_id',
        'name_snapshot',
        'rate_snapshot',
        'taxable_amount',
        'tax_amount',
    ];

    protected function casts(): array
    {
        return [
            'rate_snapshot' => 'decimal:4',
            'taxable_amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
        ];
    }

    public function saleItem(): BelongsTo
    {
        return $this->belongsTo(SaleItem::class);
    }

    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class);
    }
}