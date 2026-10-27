<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Store extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'business_id',
        'manager_user_id',
        'code',
        'name',
        'phone_line_1',
        'phone_line_2',
        'email',
        'address_line_1',
        'address_line_2',
        'neighborhood',
        'city',
        'state',
        'postal_code',
        'country_code',
        'timezone',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_user_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'store_user')
            ->withPivot('is_default', 'is_active', 'job_title', 'assigned_at')
            ->withTimestamps();
    }

    public function terminals(): HasMany
    {
        return $this->hasMany(PosTerminal::class);
    }

    public function documentSequences(): HasMany
    {
        return $this->hasMany(DocumentSequence::class);
    }
}