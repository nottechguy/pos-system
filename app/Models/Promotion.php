<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Promotion extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'business_id',
        'name',
        'description',
        'banner_image',
        'promotion_type',
        'promotional_price',
        'minimum_quantity',
        'starts_at',
        'ends_at',
        'is_active',
        'created_by_user_id',
    ];

    protected function casts(): array
    {
        return [
            'promotional_price' => 'decimal:2',
            'minimum_quantity' => 'decimal:2',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'promotion_product');
    }

    public function getDisplayPriceAttribute()
    {
        if ($this->promotional_price > 0) {
            return '$' . number_format($this->promotional_price, 2);
        }
        
        // Sum individual product promotional prices if global is zero
        $sum = $this->products->sum(function($product) {
            return $product->pivot->promotional_price ?? 0;
        });

        if ($sum > 0) {
            return '$' . number_format($sum, 2) . ' (Personalizado)';
        }

        return 'Variable / Descuento';
    }
}