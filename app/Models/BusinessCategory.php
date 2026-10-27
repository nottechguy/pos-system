<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BusinessCategory extends Model {
    protected $fillable = [
        'code',
        'name',
    ];

    public function businesses(): HasMany {
        return $this->hasMany(Business::class, 'category_id');
    }
}