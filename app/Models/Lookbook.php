<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ResolvesMediaUrls;

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
