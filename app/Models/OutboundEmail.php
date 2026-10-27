<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class OutboundEmail extends Model
{
    protected $fillable = [
        'business_id',
        'user_id',
        'related_type',
        'related_id',
        'recipient_email',
        'subject',
        'status',
        'provider_message_id',
        'failure_message',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Resolves the polymorphic entity that this email is about (e.g., Quotation)
     */
    public function related(): MorphTo
    {
        return $this->morphTo();
    }
}