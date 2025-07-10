<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'name',
        'description',
        'price',
        'quantity',
        'crust',
        'crust_price',
        'observation',
        'total',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
