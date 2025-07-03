<?php

namespace App\Http\Controllers;

use App\Helpers\Base62Helper;
use App\Models\Categories;
use App\Models\Chat;
use App\Models\Config;
use App\Models\Crust;
use App\Models\Customer;
use App\Models\Device;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;


class ChekoutController extends Controller
{
    public function index(Request $request)
    {
        $phone = $request->query('phone');

        $customer = null;
        $semCadastro = false;
        $taxaEntrega = 0;

        if ($phone) {
            $customer = Customer::where('jid', $phone)->first();

            if ($customer) {
                session()->put('customer', $customer);
                $taxaEntrega = $customer->delivery_fee ?? 0;
            } else {
                session()->forget('customer');
                $semCadastro = true;
            }
        } else {
            $customer = session()->get('customer');
            if ($customer) {
                $taxaEntrega = $customer->delivery_fee ?? 0;
            } else {
                $semCadastro = true;
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
        $crustPrice = $request->input('crustPrice');
        $observation = $request->input('observation', '');

        $product = Product::findOrFail($productId);

        $cartItem = [
            'product_id' => $product->id,
            'name' => $product->name,
            'image' => $product->image,
            'description' => $product->description,
            'price' => $product->price,
            'quantity' => $quantity,
            'crust' => $crust,
            'crust_price' => $crustPrice,
            'observation' => $observation,
            'total' => ($product->price + $crustPrice) * $quantity, // Inclui o preço da borda, se aplicável
        ];

        $cart[] = $cartItem;

        session()->put('cart', $cart);

        $cart = session()->get('cart', []);
        return redirect()->route('checkout.home')->with('success', 'Produto adicionado ao carrinho com sucesso.');
    }

    public function addToCart2(Request $request)
    {
        // Obter o carrinho da sessão
        $cart = session()->get('cart', []);



        // Obter os dados do formulário
        $productIds = json_decode($request->input('product_ids'), true); // Convertendo de string para array
        $crustId = $request->input('crust_id');
        $observation1 = $request->input('observation1');
        $observation2 = $request->input('observation2');
        $observation3 = $request->input('observation3');

        // Verificar se foram selecionados 2 ou 3 produtos
        $selectedProductCount = count($productIds);
        if ($selectedProductCount < 2 || $selectedProductCount > 3) {
            return redirect()->back()->with('error', 'Por favor, selecione entre 2 e 3 produtos.');
        }

        // Inicializar variáveis para armazenar informações dos produtos selecionados
        $productNames = [];
        $productDescriptions = [];
        $totalPrice = 0;

        // Obter informações dos produtos e calcular o preço total
        foreach ($productIds as $productId) {
            $product = Product::findOrFail($productId);
            $productNames[] = $product->name;
            $productDescriptions[] = $product->description;
        }



        // Se houver borda selecionada, adicionar o preço da borda ao total do produto
        if ($crustId !== null) {
            $crustPrice = Crust::findOrFail($crustId)->price;
            $totalPrice += $crustPrice; // Multiplicar pelo número de produtos selecionados
        }


        // Verificar se há 3 produtos e calcular o preço total usando o maior preço
        if ($selectedProductCount >= 2) {
            $productPrices = collect();
            foreach ($productIds as $productId) {
                $product = Product::findOrFail($productId);
                $productPrices->push($product->price);
            }
            $totalPrice += $productPrices->max();
        }

        $cartItem = [
            'product_id' => implode(',', $productIds), // Combine os IDs dos produtos
            'name' => implode(' / ', $productNames), // Combine os nomes dos produtos
            'description' => implode(' / ', $productDescriptions), // Combine as descrições dos produtos
            'price' => $totalPrice, // Preço total dos produtos
            'quantity' => 1, // Definindo como 1 por enquanto, pode ser ajustado conforme necessário
            'crust' => $crustId !== null ? Crust::findOrFail($crustId)->name : 'Tradicional', // Se não houver borda selecionada, usar 'Tradicional'
            'crust_price' => $crustId !== null ? $crustPrice : 0, // Se não houver borda selecionada, preço da borda será 0
            'observation' => $observation1 . ' / ' . $observation2 . ' / ' . $observation3, // Combine as observações dos produtos
            'total' => $totalPrice, // Preço total do produto
        ];

        // Adicionar a lógica para determinar a imagem com base no número de sabores selecionados
        if ($selectedProductCount == 2) {
            $cartItem['image'] = 'assets/imagens/pizza_2_sabores.png';
        } elseif ($selectedProductCount == 3) {
            $cartItem['image'] = 'assets/imagens/pizza_3_sabores.jpg';
        }

        // Adicionar o item ao carrinho
        $cart[] = $cartItem;

        // Atualizar o carrinho na sessão
        session()->put('cart', $cart);

        // Redirecionar para a página de checkout com uma mensagem de sucesso
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


        // dd($cart);
        // Recuperar o customer da sessão
        $customer = session()->get('customer');

        // dd($customer);
        $customer = Customer::find($customer->id);

        // Obter o valor do frete da sessão
        $taxaEntrega = session('taxa_entrega', 0);

        $totalPrice = array_sum(array_column($cart, 'total')) + $taxaEntrega;

        // Obter a forma de pagamento
        $paymentMethod = $request->input('payment');
        $trocoAmount = $request->input('troco_amount', 0); // Adicione esta linha
        $observation = '';

        if ($paymentMethod == 'Dinheiro' && $request->filled('troco_amount')) {
            $trocoAmount = floatval($request->input('troco_amount'));
            if ($trocoAmount > 0) {
                $observation = 'Troco para: R$ ' . number_format($trocoAmount, 2, ',', '.');
            }
        }

        // Criar o pedido
        $order = Order::create([
            'customer_id' => $customer->id,
            'status_id' => 1,
            'total_price' =>  $totalPrice,
            'payment_method' => $paymentMethod,
            'observation' => $observation,
        ]);

        // Criar os itens do pedido
        foreach ($cart as $item) {
            // Dividir os product_ids em primário e secundário e terciário
            $productIds = explode(',', $item['product_id']);
            $primaryProductId = $productIds[0];
            $secondaryProductId = isset($productIds[1]) ? $productIds[1] : null;
            $tertiaryProductId = isset($productIds[2]) ? $productIds[2] : null;

            OrderItem::create([
                'order_id' => $order->id,
                'product_id_primary' => $primaryProductId,
                'product_id_secondary' => $secondaryProductId,
                'product_id_tertiary' => $tertiaryProductId,
                'name' => $item['name'],
                'description' => $item['description'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'crust' => $item['crust'],
                'crust_price' => $item['crust_price'],
                'observation_primary' => isset($item['observation']) && $item['observation'] !== '' ? $item['observation'] : null,
                'observation_secondary' => isset($item['observation_secondary']) && $item['observation_secondary'] !== '' ? $item['observation_secondary'] : null,
                'observation_tertiary' => isset($item['observation_tertiary']) && $item['observation_tertiary'] !== '' ? $item['observation_tertiary'] : null,
            ]);
        }

        // Disparar a notificação para todos os administradores
        $admins = User::where('role', 'admin')->get(); // Supondo que você tem uma coluna 'role' para identificar administradores
        foreach ($admins as $admin) {
            $admin->notify(new NewOrderNotification("Novo Pedido"));
        }

        // Limpar a sessão do carrinho
        session()->forget(['cart']);

        return view('front.checkout.resumo', compact('cart', 'taxaEntrega', 'totalPrice', 'customer', 'paymentMethod', 'trocoAmount'));
    }


    public function enviaImagen(Request $request)
    {
        // Recuperar o customer da sessão
        $customer = session()->get('customer');
        $customer = Customer::find($customer->id);
        $service = Chat::where('jid', $customer->jid)
            ->where('active', 1)
            ->first();
        // Obtém a imagem enviada no corpo da requisição
        $imagemBase64 = $request->input('imagem');
        // Verifica se o diretório existe, se não, cria-o
        if (!Storage::disk('public')->exists('imagens')) {
            Storage::disk('public')->makeDirectory('imagens');
        }
        // Decodifica a imagem base64 e gera um nome único para o arquivo
        $imagem = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imagemBase64));
        $nomeArquivo = uniqid() . '.png';

        // Salva a imagem na pasta de armazenamento (por exemplo, a pasta "public")
        $caminhoArquivo = 'imagens/' . $nomeArquivo;
        Storage::disk('public')->put($caminhoArquivo, $imagem);
        $session = Device::first();

        $this->sendImage($session->session, $customer->jid, asset('storage/' . $caminhoArquivo), '');

        date_default_timezone_set('America/Sao_Paulo');
        $horaAtual = Carbon::now();
        $config = Config::firstOrFail();
        $horaMais45Minutos = $horaAtual->addMinutes($config->minuts);
        $text = " Pedido feito com Sucesso .";
        $this->sendMessagem($session->session, $customer->jid, $text);

        $text = "Previsão da entrega " . $horaMais45Minutos->format('H:i');
        $this->sendMessagem($session->session, $customer->jid, $text);

        $text = "Muito Obrigado! ";
        $this->sendMessagem($session->session, $customer->jid, $text);
        $service->await_answer = 'finish';
        $service->update();

        // Limpar a sessão do carrinho e do customer
        session()->forget(['customer', 'taxa_entrega']);
    }
    public function sendImage($session, $phone, $nomeImagen, $detalhes)
    {
        $curl = curl_init();

        $send = array(
            "number" => $phone,
            "message" => array(
                "image" => array(
                    "url" => $nomeImagen // public_path('uploads/' . $nomeImagen)
                ),
                "caption" => $detalhes
            ),
            "delay" => 3
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('APP_URL_ZAP') . '/' . $session . '/messages/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($send),
            CURLOPT_HTTPHEADER => array(
                'secret: $2a$12$VruN7Mf0FsXW2mR8WV0gTO134CQ54AmeCR.ml3wgc9guPSyKtHMgC',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        //  file_put_contents(Utils::createCode() . ".txt", $response);

        curl_close($curl);
    }
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

        // Cria ou atualiza o cliente
        if (!empty($data['id'])) {
            $customer = Customer::find($data['id']);

            if (!$customer) {
                return redirect()->back()->withErrors('Cliente não encontrado.');
            }

            $customer->update($data);
        } else {
            $customer = Customer::create($data);
        }

        // Já existe: salva na sessão o cliente e a taxa de entrega dele
        session()->put('customer', $customer);
        session()->put('taxa_entrega', $customer->delivery_fee ?? 0);

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
