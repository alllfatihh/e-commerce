<?php

namespace App\Models;

use App\Traits\ResolvesMediaUrls;
use Illuminate\Database\Eloquent\Model;

class Fotoshoot extends Model
{
    use ResolvesMediaUrls;

    protected $guarded = [];

    public function lookbook()
    {
        return $this->belongsTo(Lookbook::class, 'lookbook_id');
    }

    public function getImageAttribute($value)
    {
        return $this->resolveMediaUrl($value);
    }
}
