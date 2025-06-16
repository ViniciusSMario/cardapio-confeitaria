<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'delivery_type', 'address_id', 'shipping_cost', 'total', 'status'];

    public function items() {
        return $this->hasMany(OrderItem::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
