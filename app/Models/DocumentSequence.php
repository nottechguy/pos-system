<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentSequence extends Model
{
    protected $fillable = [
        'store_id',
        'document_type',
        'prefix',
        'next_number',
        'padding',
        'reset_period',
        'last_reset_at',
    ];

    protected function casts(): array
    {
        return [
            'next_number' => 'integer',
            'padding' => 'integer',
            'last_reset_at' => 'datetime',
        ];
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Generates the formatted document number safely.
     * Note: Generation and incrementing must happen within a lockForUpdate() transaction block.
     */
    public function formatNextNumber(): string
    {
        $padded = str_pad((string) $this->next_number, $this->padding, '0', STR_PAD_LEFT);
        return $this->prefix ? "{$this->prefix}{$padded}" : $padded;
    }
}