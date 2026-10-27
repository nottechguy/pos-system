<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'business_id',
        'role_id',
        'code',
        'first_name',
        'last_name',
        'email',
        'phone',
        'avatar_path',
        'password',
        'pin_hash',
        'status',
        'email_verified_at',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'pin_hash',
        'remember_token',
    ];

    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'pin_hash' => 'hashed',
        ];
    }

    public function business(): BelongsTo {
        return $this->belongsTo(Business::class);
    }

    public function role(): BelongsTo {
        return $this->belongsTo(Role::class);
    }

    public function permissions(): BelongsToMany {
        return $this->belongsToMany(Permission::class, 'permission_user')
            ->withPivot('effect')
            ->withTimestamps();
    }

    public function stores(): BelongsToMany {
        return $this->belongsToMany(Store::class, 'store_user')
            ->withPivot('is_default', 'is_active', 'job_title', 'assigned_at')
            ->withTimestamps();
    }
    
    public function getFullNameAttribute(): string {
        return trim("{$this->first_name} {$this->last_name}");
    }
}