<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fotoshoot extends Model
{
    protected $guarded = [];

    public function lookbook()
    {
        return $this->belongsTo(Lookbook::class, 'lookbook_id');
    }
}
