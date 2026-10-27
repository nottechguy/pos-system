<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Business extends Model {
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'legal_name',
        'tax_id',
        'bio',
        'phone_line_1',
        'phone_line_2',
        'email',
        'website',
        'currency_code',
        'timezone',
        'logo_path',
    ];

    public function category(): BelongsTo {
        return $this->belongsTo(BusinessCategory::class, 'category_id');
    }

    public function users(): HasMany {
        return $this->hasMany(User::class);
    }

    public function stores(): HasMany {
        return $this->hasMany(Store::class);
    }

    public function departments(): HasMany {
        return $this->hasMany(Department::class);
    }

    public function products(): HasMany {
        return $this->hasMany(Product::class);
    }

    public function productCategories(): HasMany {
        return $this->hasMany(ProductCategory::class);
    }

    public function customers(): HasMany {
        return $this->hasMany(Customer::class);
    }

    public function suppliers(): HasMany {
        return $this->hasMany(Supplier::class);
    }

    public function taxes(): HasMany {
        return $this->hasMany(Tax::class);
    }

    public function settings(): HasMany {
        return $this->hasMany(Setting::class);
    }

    public function roles(): HasMany {
        return $this->hasMany(Role::class);
    }

    public function measurementUnits(): HasMany {
        return $this->hasMany(MeasurementUnit::class);
    }

    public function priceLists(): HasMany {
        return $this->hasMany(PriceList::class);
    }

    public function documentSequences(): HasMany {
        return $this->hasMany(DocumentSequence::class);
    }
}