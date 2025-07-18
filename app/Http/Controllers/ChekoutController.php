<?php

namespace App\Http\Controllers;

use App\Helpers\Base62Helper;
use App\Helpers\MessageHelper;
use App\Models\Categories;
use App\Models\Chat;
use App\Models\Config;
use App\Models\Crust;
use App\Models\Customer;
use App\Models\Device;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPayment;
use App\Models\Product;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class ChekoutController extends Controller
{
    public function index($phone = null)
    {
        $customer = null;
        $semCadastro = false;
        $taxaEntrega = 0;

        if ($phone) {
            $customer = Customer::where('jid', $phone)->first();
            if ($customer) {
                session()->put('customer', $customer);
                $taxaEntrega = $customer->delivery_fee ?? 0;

                $chatAtivo = Chat::where('jid', $customer->jid)->where('active', 1)->first();
                if (!$chatAtivo) {
                    Chat::create([
                        'jid' => $customer->jid,
                        'session_id' => null,
                        'service_id' => null,
                        'active' => 1,
                        'await_answer' => null,
                        'flow_stage' => 'fazendo_pedido',
                    ]);
                } else {
                    $chatAtivo->update(['flow_stage' => 'fazendo_pedido']);
                }
            } else {
                session()->forget('customer');
                $semCadastro = true;

                // Cria chat mesmo sem cliente
                $chatExistente = Chat::where('jid', $phone)->where('active', 1)->first();
                if (!$chatExistente) {
                    Chat::create([
                        'jid' => $phone,
                        'session_id' => null,
                        'service_id' => null,
                        'active' => 1,
                        'await_answer' => null,
                        'flow_stage' => 'fazendo_pedido',
                    ]);
                } else {
                    $chatExistente->update(['flow_stage' => 'fazendo_pedido']);
                }
            }
        }


        session()->put('taxa_entrega', $taxaEntrega);

        $categories = Categories::with('products')->get();
        $cart = session()->get('cart', []);

        return view('front.checkout.index', compact('categories', 'cart', 'customer', 'semCadastro'));
    }


    public function addProduto($id)
    {
        $product = Product::findOrFail($id);
        $crusts = Crust::all(); // Busca todas as bordas disponíveis

        return view('front.checkout.addProduct', compact('product', 'crusts'));
    }
    public function add2Sabores()
    {
        $categories = Categories::where('name', 'LIKE', '%Pizzas%')->get(); // Busca as categorias que contêm a palavra "Pizzas" no nome
        $crusts = Crust::all(); // Busca todas as bordas disponíveis
        $products = collect(); // Cria uma coleção vazia para armazenar os produtos

        // Percorre todas as categorias encontradas
        foreach ($categories as $category) {
            // Adiciona os produtos da categoria atual à coleção de produtos
            $products = $products->merge($category->products);
        }
        return view('front.checkout.add2Sabores', compact('products', 'crusts'));
    }


    public function addToCart(Request $request)
    {
        $cart = session()->get('cart', []);

        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');
        $crust = $request->input('crust', 'Tradicional');
        $crustPrice = floatval($request->input('crustPrice', 0));
        $observation = $request->input('observation', '');
        $isBroto = $request->boolean('is_broto');

        $product = Product::findOrFail($productId);
        $productPrice = floatval($product->price);

        // Aplica desconto de R$ 10,00 se for broto
        if ($isBroto) {
            $productPrice -= 10;
            if ($productPrice < 0) {
                $productPrice = 0; // Evita total negativo
            }
        }

        $cartItem = [
            'product_id' => $product->id,
            'name' => $product->name . ($isBroto ? ' (Broto)' : ''),
            'image' => $product->image,
            'description' => $product->description,
            'price' => $productPrice,
            'quantity' => $quantity,
            'crust' => $crust,
            'crust_price' => $crustPrice,
            'observation' => $observation,
            'is_broto' => $isBroto,
            'total' => ($productPrice + $crustPrice) * $quantity,
        ];

        $cart[] = $cartItem;
        session()->put('cart', $cart);

        return redirect()->route('checkout.home')->with('success', 'Produto adicionado ao carrinho com sucesso.');
    }
    
    public function addToCart2(Request $request)
    {
        $cart = session()->get('cart', []);

        $isBroto = $request->has('is_broto') && $request->input('is_broto') == '1';

        $productIds = json_decode($request->input('product_ids'), true);
        $crustId = $request->input('crust_id');
        $observation1 = $request->input('observation1');
        $observation2 = $request->input('observation2');
        $observation3 = $request->input('observation3');

        $selectedProductCount = count($productIds);
        if ($selectedProductCount < 2 || $selectedProductCount > 3) {
            return redirect()->back()->with('error', 'Por favor, selecione entre 2 e 3 produtos.');
        }

        $productNames = [];
        $productDescriptions = [];
        $productPrices = [];

        foreach ($productIds as $productId) {
            $product = Product::findOrFail($productId);
            $productNames[] = $product->name;
            $productDescriptions[] = $product->description;
            $productPrices[] = floatval($product->price);
        }

        // Define o preço base (maior preço entre os sabores)
        $basePrice = max($productPrices);

        // Aplica desconto se for broto
        if ($isBroto) {
            $basePrice = max(0, $basePrice - 10);
        }

        // Define borda
        $crustPrice = 0;
        $crustName = 'Tradicional';
        if ($crustId !== null) {
            $crust = Crust::findOrFail($crustId);
            $crustPrice = floatval($crust->price);
            $crustName = $crust->name;
        }

        $cartItem = [
            'product_id' => implode(',', $productIds),
            'name' => implode(' / ', $productNames) . ($isBroto ? ' (Broto)' : ''),
            'description' => implode(' / ', $productDescriptions),
            'price' => $basePrice,
            'quantity' => 1,
            'crust' => $crustName,
            'crust_price' => $crustPrice,
            'observation' => $observation1 . ' / ' . $observation2 . ' / ' . $observation3,
            'total' => $basePrice + $crustPrice, // valor separado
            'is_broto' => $isBroto,
        ];

        // Define imagem com base na quantidade de sabores
        if ($selectedProductCount == 2) {
            $cartItem['image'] = 'assets/imagens/pizza_2_sabores.png';
        } elseif ($selectedProductCount == 3) {
            $cartItem['image'] = 'assets/imagens/pizza_3_sabores.jpg';
        }

        $cart[] = $cartItem;
        session()->put('cart', $cart);

        return redirect()->route('checkout.home')->with('success', 'Produto(s) adicionado(s) ao carrinho com sucesso.');
    }


    public function showCart()
    {
        $customer = optional(session('customer')); // Evita erro se for null

        if (!$customer->zip_code) {
            return view('front.checkout.cadastro', compact('customer'));
        }

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return view('front.checkout.va_pro_zap');
        }

        $produtosBebidas = Product::whereHas('category', function ($query) {
            $query->where('name', 'bebidas');
        })->get();

        return view('front.checkout.cart', compact('cart', 'produtosBebidas', 'customer'));
    }


    public function removeCartItem($index)
    {
        $cart = session()->get('cart', []);
        unset($cart[$index]);
        session()->put('cart', array_values($cart));

        return back()->with('success', 'Removido com sucesso.');
    }

    public function updateCartItemQuantity($index, $quantity)
    {
        $cart = session()->get('cart', []);

        // Verificar se a quantidade é menor ou igual a 0 e, se for, remover o item do carrinho
        if ($quantity <= 0) {
            unset($cart[$index]);
        } else {
            // Atualizar a quantidade do item no carrinho
            $cart[$index]['quantity'] = $quantity;

            // Calcular o novo total do item
            $itemTotal = 0;

            // Obter os IDs dos produtos e inicializar uma array para armazenar os preços
            $productIds = explode(',', $cart[$index]['product_id']);
            $productPrices = [];

            // Iterar sobre os IDs dos produtos para obter os preços
            foreach ($productIds as $productId) {
                $product = Product::findOrFail($productId);
                $productPrices[] = $product->price;
            }

            // Determinar o preço do item como o maior preço entre os produtos selecionados
            $itemPrice = max($productPrices);

            // Calcular o total do item com base na nova quantidade e no preço do item
            $itemTotal = $itemPrice * $quantity;

            // Verificar se há uma borda e, se houver, adicionar ao total
            if (isset($cart[$index]['crust_price'])) {
                $itemTotal += $cart[$index]['crust_price'] * $quantity;
            }

            $cart[$index]['total'] = $itemTotal;
        }

        // Calcular o total geral do carrinho
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['total']; // Usar o novo total do item
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Alterado com sucesso.');
    }

    public function finish(Request $request)
    {
        // Obter o carrinho da sessão
        $cart = session()->get('cart', []);

        // Recuperar o customer da sessão
        $customer = session()->get('customer');
        $cliente = Customer::find($customer->id);

        // Obter o valor do frete da sessão
        $taxaEntrega = session('taxa_entrega', 0);

        // Obter a forma de pagamento
        $paymentMethod = $request->input('payment');

        $trocoAmount = $request->input('troco_amount', 0);
        $observation = '';

        if ($paymentMethod == 'Dinheiro' && $request->filled('troco_amount')) {
            $trocoAmount = floatval($request->input('troco_amount'));
            if ($trocoAmount > 0) {
                $observation = 'Troco para: R$ ' . number_format($trocoAmount, 2, ',', '.');
            }
        }

        // --- Aqui começa a lógica do storeFromAdmin adaptada ---

        // Para o pedido vamos usar os produtos do carrinho do finish, mas formatar igual storeFromAdmin

        // Preparar array de produtos para a lógica do storeFromAdmin
        $todosProdutos = [];

        foreach ($cart as $item) {
            $todosProdutos[] = [
                'nome' => $item['name'],
                'valor' => $item['price'],
                'preco_borda' => $item['crust_price'] ?? 0,
                'quantidade' => $item['quantity'],
                'borda' => $item['crust'] ?? 'Tradicional',
                'observacao' => $item['observation_primary'] ?? null,
            ];
        }

        DB::beginTransaction();
        try {
            // Soma total dos itens com bordas
            $totalPedido = 0;
            foreach ($todosProdutos as $item) {
                $valor = floatval($item['valor']);
                $precoBorda = floatval($item['preco_borda'] ?? 0);
                $quantidade = intval($item['quantidade']);
                $totalItem = ($valor + $precoBorda)* $quantidade;
                $totalPedido += $totalItem;
            }

            $totalPedidoComEntrega = $totalPedido + $taxaEntrega;
            //    dd($totalPedidoComEntrega);
            // Troco (passado pelo request)
            $troco = $trocoAmount;

            // Cria pedido (sem total_price)
            $pedido = Order::create([
                'customer_id' => $cliente->id,
                'status_id' => 1,
                'change_for' => $troco > 0 ? $troco : null,
                'delivery_fee' => $taxaEntrega,
                'observation' => $observation,
            ]);

            // Salvar os itens do pedido (igual storeFromAdmin)
            foreach ($todosProdutos as $item) {
                $valor = floatval($item['valor']);
                $precoBorda = floatval($item['preco_borda'] ?? 0);
                $quantidade = intval($item['quantidade']);
                $totalItem = ($valor + $precoBorda) * $quantidade;

                OrderItem::create([
                    'order_id' => $pedido->id,
                    'name' => $item['nome'],
                    'price' => $valor,
                    'quantity' => $quantidade,
                    'crust' => $item['borda'] ?? 'Tradicional',
                    'crust_price' => $precoBorda,
                    'observation_primary' => $item['observacao'] ?? null,
                    'total' => $totalItem,
                ]);
            }

            // Salvar pagamento único (como no finish original)
            $paymentMethodId = DB::table('payment_methods')
                ->where('name', $paymentMethod)
                ->value('id');
            OrderPayment::create([
                'order_id' => $pedido->id,
                'payment_method_id' => $paymentMethodId,
                'amount' => $totalPedidoComEntrega,
            ]);

            // Atualizar estágio do Chat
            $chat = \App\Models\Chat::where('jid', $cliente->jid)->where('active', 1)->first();
            if ($chat) {
                $chat->update(['flow_stage' => 'finalizado', 'active' => 0]);
            }

            DB::commit();
            session()->forget(['cart']);

            // Retornar igual no finish original
            return view('front.checkout.resumo', [
                'cart' => $cart,
                'taxaEntrega' => $taxaEntrega,
                'totalPrice' => $totalPedidoComEntrega,
                'customer' => $cliente,
                'paymentMethod' => $paymentMethod,
                'trocoAmount' => $trocoAmount,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao salvar o pedido.',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function enviaImagen(Request $request)
    {
        $customer = session()->get('customer');
        $customer = Customer::find($customer->id);

        if (!$customer) {
            return response()->json(['success' => false, 'message' => 'Cliente não encontrado.']);
        }

        $service = Chat::where('jid', $customer->jid)
            ->first();

        if (!$service) {
            return response()->json(['success' => false, 'message' => 'Conversa ativa não encontrada.']);
        }

        // Decodifica e salva a imagem
        $imagemBase64 = $request->input('imagem');
        if (!$imagemBase64) {
            return response()->json(['success' => false, 'message' => 'Imagem não enviada.']);
        }

        $imagem = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imagemBase64));
        $nomeArquivo = uniqid() . '.png';
        $caminhoArquivo = 'imagens/' . $nomeArquivo;

        Storage::disk('public')->put($caminhoArquivo, $imagem);
        $imagemUrl = asset('storage/' . $caminhoArquivo);

        // Enviar a imagem com helper
        MessageHelper::enviarImagem($customer->jid, $imagemUrl);

        // Mensagens padrão
        $horaAtual = Carbon::now('America/Sao_Paulo');
        $config = Config::firstOrFail();
        $horaPrevista = $horaAtual->copy()->addMinutes($config->minuts);

        MessageHelper::enviarMensagem($customer->jid, 'Pedido feito com Sucesso.');
        MessageHelper::enviarMensagem($customer->jid, 'Previsão da entrega: ' . $horaPrevista->format('H:i'));
        MessageHelper::enviarMensagem($customer->jid, 'Muito Obrigado!');

        $service->update(['await_answer' => 'finish']);

        // Limpar a sessão
        session()->forget(['customer', 'taxa_entrega', 'cart']);

        return response()->json(['success' => true, 'message' => 'Imagem enviada com sucesso.']);
    }
    // public function sendImage($session, $phone, $nomeImagen, $detalhes)
    // {
    //     $curl = curl_init();

    //     $send = array(
    //         "number" => $phone,
    //         "message" => array(
    //             "image" => array(
    //                 "url" => $nomeImagen // public_path('uploads/' . $nomeImagen)
    //             ),
    //             "caption" => $detalhes
    //         ),
    //         "delay" => 3
    //     );

    //     curl_setopt_array($curl, array(
    //         CURLOPT_URL => env('APP_URL_ZAP') . '/' . $session . '/messages/send',
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_ENCODING => '',
    //         CURLOPT_MAXREDIRS => 10,
    //         CURLOPT_TIMEOUT => 0,
    //         CURLOPT_FOLLOWLOCATION => true,
    //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //         CURLOPT_CUSTOMREQUEST => 'POST',
    //         CURLOPT_POSTFIELDS => json_encode($send),
    //         CURLOPT_HTTPHEADER => array(
    //             'secret: $2a$12$VruN7Mf0FsXW2mR8WV0gTO134CQ54AmeCR.ml3wgc9guPSyKtHMgC',
    //             'Content-Type: application/json'
    //         ),
    //     ));

    //     $response = curl_exec($curl);

    //     //  file_put_contents(Utils::createCode() . ".txt", $response);

    //     curl_close($curl);
    // }
    public function sendMessagem($session, $phone, $texto)
    {


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('APP_URL_ZAP') . '/' . $session . '/messages/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                                        "number": "' . $phone . '",
                                        "message": {
                                            "text": "' . $texto . '"
                                        },
                                        "delay": 3
                                    }',
            CURLOPT_HTTPHEADER => array(
                'secret: $2a$12$VruN7Mf0FsXW2mR8WV0gTO134CQ54AmeCR.ml3wgc9guPSyKtHMgC',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        echo $response;
    }
    public function iniciar()
    {
        return view('front.checkout.iniciar');
    }
    public function updateTaxaEntrega(Request $request)
    {
        $taxaEntrega = $request->input('taxa_entrega', 0);
        session(['taxa_entrega' => $taxaEntrega]);

        return response()->json(['success' => true]);
    }
    public function pagamento(Request $request)
    {
        $data = $request->all();

        // Tratar telefone
        if (isset($data['phone'])) {
            $data['phone'] = preg_replace('/\D+/', '', $data['phone']);
            if (!str_starts_with($data['phone'], '55')) {
                $data['phone'] = '55' . $data['phone'];
            }

            $data['jid'] = $data['phone'];
            unset($data['phone']);
        }

        // Verifica se já tem cliente na sessão
        if (session()->has('customer')) {
            $customerSession = session()->get('customer');
            $customer = Customer::find($customerSession->id);
            session()->put('customer', $customer);
            session()->put('taxa_entrega', $customer->delivery_fee ?? 0);
        } else {
            if (!empty($data['id'])) {
                $customer = Customer::find($data['id']);

                if (!$customer) {
                    return redirect()->back()->withErrors('Cliente não encontrado.');
                }

                $customer->update($data);
            } else {
                $customer = Customer::create($data);
            }

            session()->put('customer', $customer);
            session()->put('taxa_entrega', $customer->delivery_fee ?? 0);
        }

        // ✅ Atualiza ou cria Chat com flow_stage = 'fazendo_cadastro'
        if (!empty($customer->jid)) {
            $chat = \App\Models\Chat::where('jid', $customer->jid)->where('active', 1)->first();

            if ($chat) {
                $chat->update(['flow_stage' => 'fazendo_cadastro']);
            } else {
                \App\Models\Chat::create([
                    'jid' => $customer->jid,
                    'active' => 1,
                    'flow_stage' => 'fazendo_cadastro',
                ]);
            }
        }

        // Obter carrinho
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return view('front.checkout.va_pro_zap');
        }

        $produtosBebidas = Product::whereHas('category', function ($query) {
            $query->where('name', 'bebidas');
        })->get();

        return view('front.checkout.payments', compact('produtosBebidas', 'cart'));
    }




    public function gerarPix(Request $request)
    {
        // Valores do pedido
        $amount = $request->input('amount');
        $cpf = '37785652813';
        $phone = '986123660';
        $reference_id = uniqid('pix_'); // ID único para referência do pedido

        // Configure o cliente Pagar.me
        $client = new \GuzzleHttp\Client();

        // Crie a transação PIX
        $response = $client->request('POST', 'https://api.pagar.me/core/v5/orders', [
            'body' => json_encode([
                'customer' => [
                    'phones' => [
                        'home_phone' => [
                            'country_code' => '55',
                            'area_code' => '11',
                            'number' => $phone
                        ]
                    ],
                    'name' => 'Cliente',
                    'email' => 'cliente@example.com',
                    'document' => $cpf,
                    'type' => 'individual'
                ],
                'items' => [
                    [
                        'amount' => $amount,
                        'description' => 'Descrição do Pedido',
                        'quantity' => 1,
                        'code' => $reference_id
                    ]
                ],
                'payments' => [
                    [
                        'payment_method' => 'pix',
                        'pix' => [
                            'expires_in' => 3600 // Tempo de expiração do PIX em segundos
                        ]
                    ]
                ]
            ]),
            'headers' => [
                'accept' => 'application/json',
                'authorization' => 'Basic ' . base64_encode(env('PAGARME_API_KEY') . ':'),
                'content-type' => 'application/json',
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        // dd($data);
        // Retorne o código "copia e cola"
        return response()->json([
            'pix_code' => $data['charges'][0]['last_transaction']['qr_code']
        ]);
    }
}
