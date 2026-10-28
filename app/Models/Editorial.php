<?php

namespace App\Models;

use App\Traits\ResolvesMediaUrls;
use Illuminate\Database\Eloquent\Model;

class Editorial extends Model
{
    use ResolvesMediaUrls;

    protected $guarded = [];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function getImageAttribute($value)
    {
        return $this->resolveMediaUrl($value);
    }

    public function getVideoUrlAttribute($value)
    {
        return $this->resolveMediaUrl($value);
    }
}
