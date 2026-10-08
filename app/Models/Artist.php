<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    protected $guarded = [];

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
}
