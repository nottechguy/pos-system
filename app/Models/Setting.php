<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Setting extends Model
{
    protected $fillable = [
        'scope_type',
        'scope_id',
        'key',
        'value',
        'is_encrypted',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'array', // Automatically handles JSON serialization
            'is_encrypted' => 'boolean',
        ];
    }

    /**
     * Resolves the polymorphic scope (Business, Store, User, or PosTerminal)
     */
    public function scope(): MorphTo
    {
        return $this->morphTo();
    }
}