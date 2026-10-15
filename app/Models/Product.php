<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ResolvesMediaUrls;

class Product extends Model
{
    use ResolvesMediaUrls;

    protected $guarded = [];

    public function stocks()
    {
        return $this->hasMany(ProductStock::class);
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
