<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Editorial extends Model
{
    protected $guarded = [];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }
}
