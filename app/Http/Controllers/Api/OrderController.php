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
            'customer', // sem .address, pois os campos estão diretos
            'items.product',
            'pagamentos.paymentMethod',
            'status',
            'motoboy'
        ])
            ->whereBetween('created_at', [$inicio, $fim])
            ->orderBy('created_at', 'desc')
            ->get();


        return response()->json([
            'success' => true,
            'data' => $pedidos
        ]);
    }
}
