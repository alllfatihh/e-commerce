<?php

namespace App\Models;

use App\Traits\ResolvesMediaUrls;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use ResolvesMediaUrls;

    protected $guarded = [];

    protected $appends = ['effective_price', 'is_discounted'];

    protected function casts(): array
    {
        return [
            'price' => 'float',
            'discount_price' => 'float',
            'model_height' => 'integer',
            'model_weight' => 'integer',
        ];
    }

    public function stocks()
    {
        return $this->hasMany(ProductStock::class);
    }

    public function discountCodes()
    {
        return $this->belongsToMany(DiscountCode::class, 'discount_code_product');
    }

    public function hasDiscount(): bool
    {
        return ! empty($this->discount_price) && (float) $this->discount_price > 0 && (float) $this->discount_price < (float) $this->price;
    }

    public function getIsDiscountedAttribute(): bool
    {
        return $this->hasDiscount();
    }

    public function getEffectivePriceAttribute(): float
    {
        return $this->hasDiscount() ? (float) $this->discount_price : (float) $this->price;
    }

    public function getImageAttribute($value)
    {
        return $this->resolveMediaUrl($value);
    }

    public function getBackImageAttribute($value)
    {
        return $this->resolveMediaUrl($value);
    }

    public function getFootageImageAttribute($value)
    {
        return $this->resolveMediaUrl($value);
    }

    public function getSizeChartImageAttribute($value)
    {
        return $this->resolveMediaUrl($value);
    }
}
