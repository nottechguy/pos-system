<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuotationItem extends Model
{
    protected $fillable = [
        'quotation_id',
        'product_id',
        'unit_id',
        'sku_snapshot',
        'barcode_snapshot',
        'name_snapshot',
        'description_snapshot',
        'unit_snapshot',
        'quantity',
        'unit_price',
        'discount_total',
        'tax_rate_snapshot',
        'tax_total',
        'line_total',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:4',
            'unit_price' => 'decimal:4',
            'discount_total' => 'decimal:2',
            'tax_rate_snapshot' => 'decimal:4',
            'tax_total' => 'decimal:2',
            'line_total' => 'decimal:2',
            'sort_order' => 'integer',
        ];
    }

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(MeasurementUnit::class);
    }
}