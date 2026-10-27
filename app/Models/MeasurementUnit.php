<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MeasurementUnit extends Model
{
    protected $fillable = [
        'code',
        'name',
        'symbol',
        'unit_type',
        'decimal_places',
    ];

    protected function casts(): array
    {
        return [
            'decimal_places' => 'integer',
        ];
    }

    public function inventoryProducts(): HasMany
    {
        return $this->hasMany(Product::class, 'inventory_unit_id');
    }

    public function saleProducts(): HasMany
    {
        return $this->hasMany(Product::class, 'sale_unit_id');
    }
}