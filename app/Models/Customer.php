<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'business_id',
        'code',
        'customer_type',
        'first_name',
        'last_name',
        'business_name',
        'tax_id',
        'email',
        'phone',
        'notes',
        'email_marketing_consent',
        'push_marketing_consent',
        'consent_updated_at',
        'is_active',
        'price_list_id'
    ];

    protected function casts(): array
    {
        return [
            'email_marketing_consent' => 'boolean',
            'push_marketing_consent' => 'boolean',
            'consent_updated_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(CustomerAddress::class);
    }

    public function devices(): HasMany
    {
        return $this->hasMany(CustomerDevice::class);
    }

    public function creditAccount(): HasOne
    {
        return $this->hasOne(CreditAccount::class);
    }

    /**
     * Helper accessor to always return a valid display name.
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->customer_type === 'business' && $this->business_name) {
            return $this->business_name;
        }

        return trim("{$this->first_name} {$this->last_name}");
    }

    public function priceList(): BelongsTo
    {
        return $this->belongsTo(PriceList::class);
    }

    public function customPrices(): HasMany
    {
        return $this->hasMany(CustomerProductPrice::class);
    }
}