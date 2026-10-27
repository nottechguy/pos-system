<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SaleItem extends Model
{
    protected $fillable = [
        'sale_id',
        'product_id',
        'unit_id',
        'price_list_id',
        'sku_snapshot',
        'barcode_snapshot',
        'name_snapshot',
        'unit_snapshot',
        'quantity',
        'unit_price',
        'unit_cost_snapshot',
        'gross_total',
        'discount_total',
        'tax_total',
        'line_total',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:4',
            'unit_price' => 'decimal:4',
            'unit_cost_snapshot' => 'decimal:4',
            'gross_total' => 'decimal:2',
            'discount_total' => 'decimal:2',
            'tax_total' => 'decimal:2',
            'line_total' => 'decimal:2',
        ];
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(MeasurementUnit::class);
    }

    public function priceList(): BelongsTo
    {
        return $this->belongsTo(PriceList::class);
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(SaleItemDiscount::class);
    }

    public function taxes(): HasMany
    {
        return $this->hasMany(SaleItemTax::class);
    }
}