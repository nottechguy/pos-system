<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashCountLine extends Model
{
    protected $fillable = [
        'cash_shift_id',
        'denomination_id',
        'quantity',
        'total',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'total' => 'decimal:2',
        ];
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(CashShift::class, 'cash_shift_id');
    }

    public function denomination(): BelongsTo
    {
        return $this->belongsTo(CashDenomination::class);
    }
}