<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ResolvesMediaUrls;

class Artwork extends Model
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
}
