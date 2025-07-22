<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;

class OrderController extends Controller
{

    public function index()
    {
        $agora = Carbon::now();

        if ($agora->hour < 4) {
            // Ainda conta como "ontem"
            $inicio = $agora->copy()->subDay()->setTime(4, 0, 0); // ontem às 04:00
            $fim = $agora;
        } else {
            // Dia atual a partir das 04:00
            $inicio = $agora->copy()->setTime(4, 0, 0);
            $fim = Carbon::now(); // agora
        }

        $pedidos = Order::with([
            'customer',
            'items',
            'pagamentos.paymentMethod',
            'status',
            'motoboy'
        ])
            ->whereBetween('created_at', [$inicio, $fim])
            ->where('status_id', 2)
            ->get()
            ->groupBy(fn($pedido) => strtolower($pedido->customer->bairro ?? '')) // agrupando por bairro em minúsculas
            ->flatMap(fn($group) => $group); // junta de volta em uma lista linear mantendo agrupamento



        return response()->json([
            'success' => true,
            'data' => $pedidos
        ]);
    }
}
