<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AuditLog extends Model
{
    // Audit logs are strictly append-only
    public const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'store_id',
        'terminal_id',
        'action',
        'auditable_type',
        'auditable_id',
        'old_values',
        'new_values',
        'request_uuid',
        'ip_address',
        'user_agent',
        'metadata',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
            'metadata' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function terminal(): BelongsTo
    {
        return $this->belongsTo(PosTerminal::class);
    }

    /**
     * Resolves the specific entity that was created, updated, or deleted.
     */
    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }
}