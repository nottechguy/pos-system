<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    protected $fillable = [
        'business_id',
        'code',
        'name',
        'requires_reference',
        'affects_cash_drawer',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'requires_reference' => 'boolean',
            'affects_cash_drawer' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function salePayments(): HasMany
    {
        return $this->hasMany(SalePayment::class);
    }

    public function saleReturnPayments(): HasMany
    {
        return $this->hasMany(SaleReturnPayment::class);
    }
}