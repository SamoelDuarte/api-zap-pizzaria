<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'total_price',
        'created_at',
        'status_id',
        'payment_method',
        'observation',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'status_id');
    }

    public function getDisplayDataAttribute()
    {
        $data = Carbon::parse($this->created_at);
        $hoje = Carbon::now();
        $horaFormatada = $data->format('H:i');

        if ($data->isSameDay($hoje)) {
            return 'HOJE as ' . $horaFormatada;
        }

        $ontem = $hoje->copy()->subDay();
        if ($data->isSameDay($ontem)) {
            return 'ONTEM as  ' . $horaFormatada;
        }

        $diferencaDias = $data->diffInDays($hoje);
        if ($diferencaDias <= 6) {
            return 'Há ' . $diferencaDias . ' dias as ' . $horaFormatada;
        }

        return $data->format('d/m/Y');
    }
}
