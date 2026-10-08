<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArtworkOffering extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'artwork_id',
        'offering_price',
        'status',
        'reference_number'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function artwork()
    {
        return $this->belongsTo(Artwork::class);
    }
}
