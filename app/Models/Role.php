<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model {
    protected $fillable = [
        'code',
        'name',
        'description',
        'is_system',
    ];

    protected function casts(): array {
        return [
            'is_system' => 'boolean',
        ];
    }

    public function permissions(): BelongsToMany {
        return $this->belongsToMany(Permission::class, 'permission_role')
            ->withTimestamps();
    }

    public function users(): HasMany {
        return $this->hasMany(User::class);
    }
}