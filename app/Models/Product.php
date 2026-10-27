<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'business_id',
        'department_id',
        'category_id',
        'inventory_unit_id',
        'sale_unit_id',
        'net_content_unit_id',
        'sku',
        'name',
        'description',
        'product_type',
        'allows_fractional_qty',
        'sale_to_inventory_factor',
        'net_content_quantity',
        'track_inventory',
        'allow_negative_inventory',
        'target_margin_percent',
        'is_active',
        'created_by_user_id',
        'updated_by_user_id',
    ];

    protected function casts(): array
    {
        return [
            'allows_fractional_qty' => 'boolean',
            'track_inventory' => 'boolean',
            'allow_negative_inventory' => 'boolean',
            'is_active' => 'boolean',
            'sale_to_inventory_factor' => 'decimal:2',
            'net_content_quantity' => 'decimal:2',
            'target_margin_percent' => 'decimal:2',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function inventoryUnit(): BelongsTo
    {
        return $this->belongsTo(MeasurementUnit::class, 'inventory_unit_id');
    }

    public function saleUnit(): BelongsTo
    {
        return $this->belongsTo(MeasurementUnit::class, 'sale_unit_id');
    }

    public function netContentUnit(): BelongsTo
    {
        return $this->belongsTo(MeasurementUnit::class, 'net_content_unit_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_user_id');
    }

    public function prices(): HasMany {
        return $this->hasMany(ProductPrice::class);
    }

    public function currentPrice(): HasOne
    {
        return $this->hasOne(ProductPrice::class)
            ->where(function ($query) {
                $query->whereNull('valid_until')
                      ->orWhere('valid_until', '>=', now());
            })
            ->where('valid_from', '<=', now())
            ->latestOfMany(); // Gets the most recently created valid price
    }

    public function getSalePriceAttribute()
    {
        // Automatically fall back to the loaded relationship or lazy-load it
        return $this->currentPrice?->sale_price ?? 0.00;
    }

    public function barcodes(): HasMany
    {
        return $this->hasMany(ProductBarcode::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function suppliers(): BelongsToMany
    {
        return $this->belongsToMany(Supplier::class, 'supplier_product')
            ->withPivot('supplier_sku', 'last_cost', 'minimum_order_quantity', 'lead_time_days', 'is_preferred')
            ->withTimestamps();
    }

    // Products contained inside this product (if this product is a 'kit')
    public function components(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_components', 'kit_product_id', 'component_product_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    // Kits that contain this product
    public function kits(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_components', 'component_product_id', 'kit_product_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function quantityRules(): HasMany
    {
        return $this->hasMany(ProductQuantityRule::class);
    }
}