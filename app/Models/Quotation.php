<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Quotation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'business_id',
        'store_id',
        'author_user_id',
        'customer_id',
        'number',
        'status',
        'currency_code',
        'customer_name_snapshot',
        'customer_email_snapshot',
        'customer_phone_snapshot',
        'subtotal',
        'discount_total',
        'tax_total',
        'total',
        'valid_until',
        'sent_at',
        'accepted_at',
        'rejected_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount_total' => 'decimal:2',
            'tax_total' => 'decimal:2',
            'total' => 'decimal:2',
            'valid_until' => 'datetime',
            'sent_at' => 'datetime',
            'accepted_at' => 'datetime',
            'rejected_at' => 'datetime',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_user_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuotationItem::class);
    }

    /**
     * Resolves all outbound emails related to this specific quotation.
     */
    public function emails(): MorphMany
    {
        return $this->morphMany(OutboundEmail::class, 'related');
    }
}