<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ResolvesMediaUrls;

class SiteMedia extends Model
{
    use HasFactory, ResolvesMediaUrls;

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function getImageAttribute($value)
    {
        return $this->resolveMediaUrl($value);
    }
}
