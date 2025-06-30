<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $guarded = [];

    protected $primaryKey = 'code';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $hidden = ['deleted_at'];

    protected $appends = ['current_price'];

    public function prices(): HasMany
    {
        return $this->hasMany(ProductPrice::class, 'product_code', 'code');
    }

    public function getCurrentPriceAttribute(): ?float
    {
        return $this->prices->where('year', Carbon::now()->year)->first()->price ?? null;
    }
}
