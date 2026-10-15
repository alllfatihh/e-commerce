<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ResolvesMediaUrls;

class Artist extends Model
{
    use ResolvesMediaUrls;

    protected $guarded = [];

    protected $casts = [
        'is_newcomer' => 'boolean',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'artist', 'name');
    }

    public function artworks()
    {
        return $this->hasMany(Artwork::class);
    }

    public function editorials()
    {
        return $this->hasMany(Editorial::class);
    }

    public function getPhotoAttribute($value)
    {
        return $this->resolveMediaUrl($value);
    }

    public function getArtworkPreviewAttribute($value)
    {
        return $this->resolveMediaUrl($value);
    }

    public function getPortraitImageAttribute()
    {
        return $this->photo;
    }

    public function getArtworkImageAttribute()
    {
        return $this->artwork_preview;
    }
}
