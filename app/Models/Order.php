<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    protected $casts = [
        'received_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function canBeConfirmedReceived(): bool
    {
        return $this->status === 'delivered' || $this->biteship_status === 'delivered';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
}
