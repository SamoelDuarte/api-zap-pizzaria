<?php

namespace App\Http\Controllers;

use App\Helpers\MessageHelper;
use App\Models\Motoboy;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ForneiroController extends Controller
{
    /**
     * Lista pedidos para o forneiro (mobile)
     * Mostra pedidos de hoje (a partir das 4h) com status "Em preparo" (status_id = 2)
     */
    public function index()
    {
        $agora = Carbon::now();

        // Lógica do horário: considera o dia a partir das 4h da manhã
        if ($agora->hour < 4) {
            $inicio = $agora->copy()->subDay()->setTime(4, 0, 0);
            $fim = $agora;
        } else {
            $inicio = $agora->copy()->setTime(4, 0, 0);
            $fim = Carbon::now();
        }

        // Busca pedidos "Em preparo" do dia
        $pedidos = Order::with([
            'customer',
            'items',
            'payments.paymentMethod',
            'status',
            'motoboy'
        ])
            ->whereBetween('created_at', [$inicio, $fim])
            ->where('status_id', 2) // Status "Em preparo"
            ->orderBy('created_at', 'asc')
            ->get()
            ->groupBy(fn($pedido) => strtolower($pedido->customer->neighborhood ?? '')) // Agrupando por bairro
            ->flatMap(fn($group) => $group); // Junta de volta em uma lista linear

        return view('admin.forneiro.index', compact('pedidos'));
    }

    /**
     * Marca pedido como feito (status "Pronto")
     */
    public function marcarFeito($id)
    {
        try {
            \Log::info("Marcando pedido como feito: " . $id);
            
            $order = Order::findOrFail($id);
            $order->status_id = 4; // Status "Pronto"
            $order->save();

            \Log::info("Pedido marcado como feito com sucesso: " . $id);

            return response()->json([
                'success' => true, 
                'message' => 'Pedido marcado como pronto com sucesso!'
            ]);
        } catch (\Exception $e) {
            \Log::error("Erro ao marcar pedido como feito: " . $e->getMessage());
            
            return response()->json([
                'success' => false, 
                'message' => 'Erro ao marcar pedido como pronto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Atribui motoboy ao pedido
     */
    public function atribuirMotoboy(Request $request)
    {
        try {
            \Log::info("Atribuindo motoboy - Request: " . json_encode($request->all()));
            
            $request->validate([
                'order_id' => 'required|exists:orders,id',
                'motoboy_id' => 'required|exists:motoboys,id',
            ]);

            // Carrega o pedido com as relações necessárias
            $order = Order::with(['customer', 'items', 'payments', 'status'])->findOrFail($request->order_id);
            $motoboy = Motoboy::findOrFail($request->motoboy_id);

            // Atualiza o pedido
            $order->motoboy_id = $motoboy->id;
            $order->status_id = 6; // Status "Saiu para entrega"
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
            $msgCliente .= "Agradecemos por pedir com a gente! ❤️";

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

            \Log::info("Motoboy atribuído com sucesso - Pedido: {$request->order_id}, Motoboy: {$motoboy->name}");

            return response()->json([
                'success' => true,
                'status_name' => $order->status->name,
                'status_color' => $order->status->color,
                'motoboy_name' => $motoboy->name,
                'message' => 'Motoboy atribuído com sucesso!'
            ]);

        } catch (\Exception $e) {
            \Log::error("Erro ao atribuir motoboy: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atribuir motoboy: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lista motoboys para atribuição
     */
    public function getMotoboys()
    {
        try {
            \Log::info("Carregando lista de motoboys");
            
            $motoboys = Motoboy::select('id', 'name', 'phone')->get();
            
            \Log::info("Motoboys carregados: " . $motoboys->count());
            
            return response()->json($motoboys);
        } catch (\Exception $e) {
            \Log::error("Erro ao carregar motoboys: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar motoboys: ' . $e->getMessage()
            ], 500);
        }
    }
}