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
        'change_for',
        'delivery_fee',
        'cancel_reason', 
    ];

    // =================== RELACIONAMENTOS ===================
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function pagamentos()
    {
        return $this->hasMany(OrderPayment::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'status_id');
    }

    // =================== FORMATAÇÃO DE DATA ===================
    public function getDisplayDataAttribute()
    {
        $data = Carbon::parse($this->created_at);
        $hoje = Carbon::now();
        $horaFormatada = $data->format('H:i');

        if ($data->isSameDay($hoje)) {
            return 'HOJE às ' . $horaFormatada;
        }

        $ontem = $hoje->copy()->subDay();
        if ($data->isSameDay($ontem)) {
            return 'ONTEM às ' . $horaFormatada;
        }

        $diferencaDias = $data->diffInDays($hoje);
        if ($diferencaDias <= 6) {
            return 'Há ' . $diferencaDias . ' dias às ' . $horaFormatada;
        }

        return $data->format('d/m/Y');
    }

    // =================== TOTAL COM TAXA DE ENTREGA ===================
    public function getTotalGeralAttribute()
    {
        $somaItens = $this->items->sum('total');
        return $somaItens + ($this->delivery_fee ?? 0);
    }

    // =================== FORMAS DE PAGAMENTO ===================
    public function getFormasPagamentoAttribute()
    {
        return $this->pagamentos->pluck('paymentMethod.name')->join(', ');
    }
    public function payments()
    {
        return $this->hasMany(OrderPayment::class);
    }
    public function motoboy()
    {
        return $this->belongsTo(Motoboy::class);
    }
}
