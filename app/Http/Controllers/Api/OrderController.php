<?php

namespace App\Http\Controllers\Api;

use App\Helpers\MessageHelper;
use App\Http\Controllers\Controller;
use App\Models\Motoboy;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

     public function atribuirMotoboy(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'motoboy_id' => 'required|exists:motoboys,id',
        ]);

        // Carrega o pedido com as relações necessárias
        $order = Order::with(['customer', 'items', 'payments', 'status'])->findOrFail($request->order_id);
        $motoboy = Motoboy::findOrFail($request->motoboy_id);

        // Atualiza o pedido
        $order->motoboy_id = $motoboy->id;
        $order->status_id = 2; // Exemplo: status "Saiu para entrega"
        $order->save();

        // -----------------------------
        // 🧾 Dados para mensagem
        // -----------------------------
        $clienteTelefone = $order->customer->phone;
        $clienteNome = $order->customer->name;
        $motoboyTelefone = $motoboy->phone;
        $enderecoCliente = $order->customer->location;
        $linkGoogleMaps = $order->customer->getLocationLink();

        // -----------------------------
        // 📩 Mensagem para o cliente
        // -----------------------------
        $msgCliente = "🍕 Olá {$clienteNome}, seu pedido está a caminho com nosso motoboy! 🛵\n\n";
        $msgCliente .= "📦 Produtos:\n";
        foreach ($order->items as $item) {
            $qtd = $item->quantity > 1 ? " x{$item->quantity}" : '';
            $msgCliente .= "- {$item->name}{$qtd} (R$ " . number_format($item->total, 2, ',', '.') . ")\n";
        }

        $msgCliente .= "\n💰 Pagamento:\n";
        foreach ($order->payments as $p) {
            $msgCliente .= "- {$p->paymentMethod->name}: R$ " . number_format($p->amount, 2, ',', '.') . "\n";
        }

        if ($order->change_for) {
            $msgCliente .= "💸 Troco: R$ " . number_format($order->change_for, 2, ',', '.') . "\n";
        }

        $msgCliente .= "\n🚚 Taxa de entrega: R$ " . number_format($order->delivery_fee, 2, ',', '.');
        $msgCliente .= "\n💵 Total: R$ " . number_format($order->total_geral, 2, ',', '.');
        $msgCliente .= "\n\nAgradecemos por pedir com a gente! ❤️";

        // -----------------------------
        // 📩 Mensagem para o motoboy
        // -----------------------------
        $msgMotoboy = "🛵 Novo pedido para entrega:\n\n";
        $msgMotoboy .= "📍 Endereço:\n{$enderecoCliente}\n";
        $msgMotoboy .= "🗺️ Rota: {$linkGoogleMaps}\n\n";

        $msgMotoboy .= "📦 Produtos:\n";
        foreach ($order->items as $item) {
            $qtd = $item->quantity > 1 ? " x{$item->quantity}" : '';
            $msgMotoboy .= "- {$item->name}{$qtd} (R$ " . number_format($item->total, 2, ',', '.') . ")\n";
        }

        $msgMotoboy .= "\n💰 Pagamento:\n";
        foreach ($order->payments as $p) {
            $msgMotoboy .= "- {$p->paymentMethod->name}: R$ " . number_format($p->amount, 2, ',', '.') . "\n";
        }

        if ($order->change_for) {
            $msgMotoboy .= "💸 Troco: R$ " . number_format($order->change_for, 2, ',', '.') . "\n";
        }

        $msgMotoboy .= "\n🚚 Entrega: R$ " . number_format($order->delivery_fee, 2, ',', '.');
        $msgMotoboy .= "\n💵 Total: R$ " . number_format($order->total_geral, 2, ',', '.');

        // -----------------------------
        // 📤 Enviar mensagens
        // -----------------------------
        MessageHelper::enviarMensagem($clienteTelefone, $msgCliente);
        MessageHelper::enviarMensagem($motoboyTelefone, $msgMotoboy);

        // Recarrega status atualizado
        $order->load('status');

        return response()->json([
            'success' => true,
            'status_name' => $order->status->name,
            'status_color' => $order->status->color,
            'motoboy_name' => $motoboy->name,
        ]);
    }
}
