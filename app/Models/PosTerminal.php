<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PosTerminal extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'store_id',
        'code',
        'name',
        'device_uuid',
        'printer_name',
        'app_version',
        'last_seen_at',
        'is_active',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'last_seen_at' => 'datetime',
            'is_active' => 'boolean',
            'metadata' => 'array',
        ];
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}