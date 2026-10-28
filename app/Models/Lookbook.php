<?php

namespace App\Models;

use App\Traits\ResolvesMediaUrls;
use Illuminate\Database\Eloquent\Model;

class Lookbook extends Model
{
    use ResolvesMediaUrls;

    protected $guarded = [];

    public function fotoshoots()
    {
        return $this->hasMany(Fotoshoot::class, 'lookbook_id')->orderBy('id', 'desc');
    }

    public function getCoverImageAttribute($value)
    {
        return $this->resolveMediaUrl($value);
    }

    public function getVideoUrlAttribute($value)
    {
        return $this->resolveMediaUrl($value);
    }
}
