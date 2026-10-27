<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyStoreSummary extends Model
{
    protected $fillable = [
        'store_id',
        'summary_date',
        'gross_sales',
        'discount_total',
        'tax_total',
        'net_sales',
        'refund_total',
        'cost_total',
        'gross_profit',
        'transaction_count',
        'calculated_at',
    ];

    protected function casts(): array
    {
        return [
            'summary_date' => 'date',
            'gross_sales' => 'decimal:2',
            'discount_total' => 'decimal:2',
            'tax_total' => 'decimal:2',
            'net_sales' => 'decimal:2',
            'refund_total' => 'decimal:2',
            'cost_total' => 'decimal:2',
            'gross_profit' => 'decimal:2',
            'transaction_count' => 'integer',
            'calculated_at' => 'datetime',
        ];
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}