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
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->whereBetween('orders.created_at', [$inicio, $fim])
            ->where('orders.status_id', 2)
            ->orderBy('customers.bairro', 'asc')
            ->orderBy('orders.created_at', 'desc')
            ->select('orders.*')
            ->get();

        $pedidos = $pedidos->groupBy(function ($pedido) {
            return strtolower(trim($pedido->customer->bairro));
        })->flatten();


        return response()->json([
            'success' => true,
            'data' => $pedidos
        ]);
    }
}
