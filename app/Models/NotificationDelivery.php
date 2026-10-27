<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationDelivery extends Model
{
    protected $fillable = [
        'campaign_id',
        'customer_id',
        'destination',
        'status',
        'provider_message_id',
        'failure_message',
        'attempt_count',
        'sent_at',
        'delivered_at',
    ];

    protected function casts(): array
    {
        return [
            'attempt_count' => 'integer',
            'sent_at' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(NotificationCampaign::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}