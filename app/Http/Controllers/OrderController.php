<?php

namespace App\Http\Controllers;

use App\Helpers\MessageHelper;
use App\Models\Config;
use App\Models\Customer;
use App\Models\Device;
use App\Models\Motoboy;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPayment;
use App\Models\OrderStatus;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Models\Whatsapp;
use App\Notifications\NewOrderNotification;
use App\Services\DistanceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->query('start_date')
            ? Carbon::parse($request->query('start_date'))->startOfDay()
            : (Carbon::now()->hour < 4 ? Carbon::yesterday() : Carbon::today());

        $end = $request->query('end_date')
            ? Carbon::parse($request->query('end_date'))->endOfDay()
            : (Carbon::now()->hour < 4 ? Carbon::today()->setHour(4) : Carbon::tomorrow()->setHour(4));

        $orders = Order::with(['customer', 'status', 'payments.paymentMethod', 'motoboy'])
            ->whereBetween('created_at', [$start, $end])
            ->get();
        $motoboys = Motoboy::all();
        Order::where('notify', 0)->update(['notify' => 1]);
        $statuses = \App\Models\OrderStatus::all();

        return view('admin.order.index', compact('orders', 'statuses', 'motoboys', 'start', 'end'));
    }

    public function motoboyLista()
    {
        return \App\Models\Motoboy::all();
    }

    public function atribuirMotoboy(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'motoboy_id' => 'required|exists:motoboys,id',
        ]);

        $order = Order::with(['customer', 'items', 'payments', 'status'])->findOrFail($request->order_id);
        $motoboy = Motoboy::findOrFail($request->motoboy_id);

        // Atualiza o pedido
        $order->motoboy_id = $motoboy->id;
        $order->status_id = 2; // Status "Saiu para entrega"
        $order->save();

        // Dados do cliente
        $clienteTelefone = $order->customer->phone;
        $clienteNome = $order->customer->name;

        // Dados do motoboy
        $motoboyTelefone = $motoboy->phone;
        $enderecoCliente = $order->customer->location;
        $linkGoogleMaps = $order->customer->getLocationLink();

        // Monta a mensagem para o cliente
        $msgCliente = "🍕 Olá {$clienteNome}, seu pedido está a caminho com nosso motoboy! 🛵\n\n";

        $msgCliente .= "📦 Produtos:\n";
        foreach ($order->items as $item) {
            $qtd = $item->quantity > 1 ? " x{$item->quantity}" : '';
            $msgCliente .= "- {$item->name}{$qtd} (R$ " . number_format($item->total, 2, ',', '.') . ")\n";
        }

        // Formas de pagamento
        $msgCliente .= "\n💰 Pagamento:\n";
        foreach ($order->payments as $p) {
            $msgCliente .= "- {$p->paymentMethod->name}: R$ " . number_format($p->amount, 2, ',', '.') . "\n";
        }

        // Troco, se houver
        if ($order->change_for) {
            $msgCliente .= "💸 Troco: R$ " . number_format($order->change_for, 2, ',', '.') . "\n";
        }

        $msgCliente .= "\n🚚 Taxa de entrega: R$ " . number_format($order->delivery_fee, 2, ',', '.');
        $msgCliente .= "\n💵 Total: R$ " . number_format($order->total_geral, 2, ',', '.');
        $msgCliente .= "\n\nAgradecemos por pedir com a gente! ❤️";

        // Monta a mensagem para o motoboy
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

        // Envia mensagens
        MessageHelper::enviarMensagem($clienteTelefone, $msgCliente);
        MessageHelper::enviarMensagem($motoboyTelefone, $msgMotoboy);

        $order->load('status'); // 🔁 Recarrega a relação após mudar o status

        return response()->json([
            'success' => true,
            'status_name' => $order->status->name,
            'status_color' => $order->status->color,
            'motoboy_name' => $motoboy->name,
        ]);
    }

    public function create()
    {
        $crusts = \App\Models\Crust::all(); // busca todas as bordas do banco
        return view('admin.order.create', compact('crusts'));
    }
    public function calcularEntrega(Request $request)
    {
        $destino = $request->input('destino');
        // dd($destino);

        $distanceService = new DistanceService();
        $km = $distanceService->getDistanceInKm($destino);

        if ($km === null) {
            return response()->json(['erro' => 'Não foi possível calcular a distância'], 400);
        }

        $taxa = $distanceService->calculateDeliveryFeeAmount($km);

        return response()->json([
            'km' => $km,
            'taxa' => number_format($taxa, 2, '.', '')
        ]);
    }

    public function getOrders()
    {
        // $orders = Order::with(['customer', 'status'])->get();

        // // Modificando os dados para incluir o atributo display_data
        // foreach ($orders as $order) {
        //     $order->display_data = $order->display_data;
        // }

        // return DataTables::of($orders)->make(true);
    }
    public function getOrdersCount()
    {
        $orders = Order::where('notify', 0)->count();
        return $orders;
    }
    public function storeFromAdmin(Request $request)
    {
        $produtosUnidade = $request->input('produtos', []);
        $pizzasMeia = $request->input('pizzas_meia', []);
        $produtosSimples = $request->input('produtos_simples', []);

        $todosProdutos = array_merge($produtosUnidade, $pizzasMeia, $produtosSimples);
        $pagamentos = $request->input('pagamentos', []);
        $telefone = preg_replace('/[^0-9]/', '', $request->input('telefone'));

        $cliente = Customer::where('jid', '55' . $telefone)->first();

        $dadosCliente = [
            'name' => $request->input('nome'),
            'zipcode' => $request->input('cep'),
            'public_place' => $request->input('logradouro'),
            'number' => $request->input('numero'),
            'neighborhood' => $request->input('bairro'),
            'city' => $request->input('cidade'),
            'complement' => $request->input('referencia'),
            'state' => 'SP',
        ];
        if (!$cliente) {
            // Se não existe, cria com os dados
            $cliente = Customer::create(array_merge(['jid' => $telefone], $dadosCliente));
        } else {
            // Se já existe, só atualiza se mudou algum dado realmente
            $mudou = false;
            foreach ($dadosCliente as $campo => $valor) {
                if ($cliente->$campo != $valor) {
                    $mudou = true;
                    break;
                }
            }

            if ($mudou) {
                $cliente->update($dadosCliente);
            }
        }



        DB::beginTransaction();
        try {
            // Soma dos itens (já com bordas)
            $totalPedido = 0;
            foreach ($todosProdutos as $item) {
                $valor = floatval($item['valor']);
                $precoBorda = floatval($item['preco_borda'] ?? 0);
                $quantidade = intval($item['quantidade']);

                $totalItem = ($valor + $precoBorda) * $quantidade;
                $totalPedido += $totalItem;
            }

            // Taxa de entrega
            $deliveryFee = floatval($request->input('delivery_fee', 0));

            // Total final do pedido
            $totalPedidoComEntrega = $totalPedido + $deliveryFee;

            // Total pago
            $totalPago = array_reduce($pagamentos, function ($carry, $pagamento) {
                return $carry + floatval($pagamento['valor']);
            }, 0);

            // Troco (se houver)
            $troco = $totalPago > $totalPedidoComEntrega ? $totalPago - $totalPedidoComEntrega : 0;


            // Verifica se algum pagamento é via Pix
            $pagamentoViaPix = collect($pagamentos)->contains(function ($pagamento) {
                return strtolower($pagamento['forma']) === 'pix';
            });
            $statusId = $pagamentoViaPix ? 1 : 2; // ou outro valor se quiser quando **não** for Pix

            $pedido = Order::create([
                'customer_id' => $cliente->id,
                'status_id' => $statusId,
                'change_for' => $troco > 0 ? $troco : null,
                'delivery_fee' => floatval($request->input('delivery_fee', 0)),
            ]);
            // Salva os itens
            foreach ($todosProdutos as $item) {
                $valor = floatval($item['valor']);
                $precoBorda = floatval($item['preco_borda'] ?? 0);
                $quantidade = intval($item['quantidade']);

                // ⚠️ Total do item inclui valor + borda
                $totalItem = ($valor + $precoBorda) * $quantidade;

                OrderItem::create([
                    'order_id' => $pedido->id,
                    'name' => $item['nome'],
                    'price' => $valor, // <- preço base, sem borda
                    'quantity' => $quantidade,
                    'crust' => $item['borda'] ?? 'Tradicional',
                    'crust_price' => $precoBorda, // <- borda separada
                    'observation' => $item['observacao'] ?? null,
                    'total' => $totalItem, // <- valor com borda
                ]);
            }


            // Salva os pagamentos
            foreach ($pagamentos as $pagamento) {
                $paymentMethodId = DB::table('payment_methods')
                    ->where('name', $pagamento['forma'])
                    ->value('id');

                OrderPayment::create([
                    'order_id' => $pedido->id,
                    'payment_method_id' => $paymentMethodId,
                    'amount' => floatval($pagamento['valor']),

                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Pedido salvo com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Erro ao salvar o pedido.', 'error' => $e->getMessage()]);
        }
    }

    public function getOrder(Request $request)
    {
        $order = Order::with(['customer', 'items'])->where('id', $request->id)->first();
        echo json_encode($order);
    }

    public function updateStatus(Request $request)
    {
        $orderId = $request->input('orderId');
        $newStatus = $request->input('newStatus');
        $device = Device::first();
        // Atualizar o status diretamente
        $order = Order::find($orderId);
        if ($newStatus === '2') {
            $config = Config::first();
            $text = "PEDIDO#" . $order->id . " Veja a rota no Google Maps:" . $order->customer->getLocationLink();
            Whatsapp::sendMessagem($device->session, '55' . $config->motoboy_fone, $text);
        }

        // Verificar se o novo status é "Saiu Para Entrega"
        if ($newStatus === '5') {


            $text2 = "🏍️ Seu pedido acabou de sair para entrega.";
            // dd( $order->customer->jid);
            Whatsapp::sendMessagem($device->session, $order->customer->jid, $text2);
        }


        // dd($request->all());
        if ($order) {
            $order->status_id = $newStatus;
            $order->save();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Pedido não encontrado.']);
        }
    }

    public function alterarStatus(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->status_id = $request->status_id;

        // Se for cancelado, salva o motivo
        if ($order->status && $order->status->name === 'Cancelado') {
            $order->cancel_reason = $request->cancel_reason;
        }

        $order->save();

        return response()->json(['success' => true]);
    }
}
