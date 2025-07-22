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
            ->groupBy(fn($pedido) => strtolower($pedido->customer->neighborhood ?? '')) // agrupando por bairro em minúsculas
            ->flatMap(fn($group) => $group); // junta de volta em uma lista linear mantendo agrupamento



        return response()->json([
            'success' => true,
            'data' => $pedidos
        ]);
    }

    public function marcarComoFeito($id)
    {
        $order = Order::findOrFail($id);
        $order->status_id = 4;
        $order->save();

        return response()->json(['success' => true, 'message' => 'Pedido finalizado']);
    }
}
