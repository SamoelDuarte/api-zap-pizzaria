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
            $inicio = $agora->copy()->subDay()->setTime(4, 0, 0);
            $fim = $agora;
        } else {
            $inicio = $agora->copy()->setTime(4, 0, 0);
            $fim = Carbon::now();
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
            ->groupBy(fn($pedido) => strtolower($pedido->customer->bairro ?? ''))
            ->sortKeys()
            ->flatMap(fn($group) => $group)
            ->values();

        return response()->json([
            'success' => true,
            'data' => $pedidos
        ]);
    }
}
