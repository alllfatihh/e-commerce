<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lookbook extends Model
{
    protected $guarded = [];

    public function fotoshoots()
    {
        return $this->hasMany(Fotoshoot::class, 'lookbook_id')->orderBy('id', 'desc');
    }
}
