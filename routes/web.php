<?php

use App\Http\Controllers\admin\ConfigController;
use App\Http\Controllers\admin\MenssageController;
use App\Http\Controllers\admin\MotoboyController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ScheduleController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChatBotController;
use App\Http\Controllers\ChekoutController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\GitWebhookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificacaoController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use League\Csv\Reader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::prefix('/webhook')->controller(WebhookController::class)->group(function () {
    Route::post('/', 'evento');
    Route::get('/', 'evento');
    Route::put('/', 'evento');
    Route::get('/deletaChat', 'deletaChat');
});


Route::post('/git-webhook', [GitWebhookController::class, 'handle']);
Route::get('/notificacoes', 'NotificacaoController@index')->name('notificacoes.index');
Route::post('/marcar-como-lida/{id}', 'NotificacaoController@marcarComoLida')->name('notificacoes.marcar_como_lida');

// routes/web.php

Route::get('/admin/notifications', 'NotificationController@index')->name('admin.notifications.index');
Route::get('/admin/notifications/mark-as-read/{id}',  'NotificationController@markAsRead')->name('admin.notifications.markAsRead');
Route::get('/admin/notifications/check', [NotificacaoController::class, 'check'])->name('admin.notifications.check');



Route::prefix('/admin')->controller(AdminController::class)->group(function () {
    Route::get('/login', 'login')->name('admin.login');
    Route::get('/sair', 'sair')->name('admin.sair');
    Route::get('/senha', 'password')->name('admin.password');
    Route::post('/attempt', 'attempt')->name('admin.attempt');

    Route::prefix('/chat')->controller(ChatBotController::class)->group(function () {
        Route::get('/getAtendimentoPedente', 'getAtendimentoPedente');
    });
});

Route::prefix('/')->controller(HomeController::class)->group(function () {
    Route::get('/', 'index');
});

// Política de Privacidade
Route::get('/politica-privacidade', function () {
    return view('privacy-policy');
})->name('privacy.policy');

Route::prefix('/checkout')->controller(ChekoutController::class)->group(function () {
    Route::get('/pedido/{phone}', 'index');
    Route::post('/generate-pix', 'gerarPix')->name('generate-pix');
    Route::get('/', 'index')->name('checkout.home');
    Route::get('/adicionar-produto/{id}', 'addProduto');
    Route::get('/adicionar-2-sabores', 'add2Sabores');
    Route::get('/cart/remove/{id}', 'removeCartItem');
    Route::get('/cart', 'showCart')->name('cart.show');
    Route::post('/enviaImagen', 'enviaImagen')->name('checkout.enviaImagen');
    Route::post('/finalizar', 'finish');
    Route::get('/iniciaratendimento', 'iniciar');
    Route::post('/pagamento', 'pagamento')->name('cart.payment');
    Route::get('/pagamento', 'pagamento')->name('cart.payment');
    Route::post('/addToCart', 'addToCart')->name('cart.add');
    Route::post('/addToCart2', 'addToCart2')->name('cart.add2');
    Route::post('/update-taxa-entrega', 'updateTaxaEntrega')->name('update-taxa-entrega');
    Route::get('/cart/update-quantity/{index}/{quantity}', 'updateCartItemQuantity')->name('cart.update-quantity');
});


Route::prefix('/events')->controller(EventsController::class)->group(function () {
    Route::post('/', 'index')->name('admin.events.index');
    Route::get('/teste', 'teste');
    Route::post('/carrinhoAbandonado', 'carrinhoAbandonado');
    Route::get('/mensagemEmMassa', 'mensagemEmMassa');
    Route::get('/avaliacao', 'avaliacao');
    Route::post('/avaliar', 'storeAvaliacao')->name('admin.events.avaliacao.store');
    Route::post('/avaliar', 'storeAvaliacao')->name('admin.events.avaliacao.store');
});
Route::prefix('/')->controller(AdminController::class)->group(function () {
    Route::get('/admin', 'login')->name('admin.login'); // Nomeie a rota como admin.login
});

Route::middleware(['auth.user'])->group(function () {

    Route::prefix('/formulario')->controller(FormController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/novo', 'create');
        Route::post('/store', 'store');
    });


    Route::middleware('auth.admin')->group(function () {

        Route::prefix('/motoboy')->controller(MotoboyController::class)->group(function () {
            Route::get('/', 'index')->name('admin.motoboy.index');
            Route::get('/novo', 'create')->name('admin.motoboy.create');
            Route::post('/criar', 'store')->name('admin.motoboy.store');
            Route::get('/editar/{motoboy}', 'edit')->name('admin.motoboy.edit');
            Route::put('/atualizar/{motoboy}', 'update')->name('admin.motoboy.update');
            Route::delete('/deletar/{motoboy}', 'destroy')->name('admin.motoboy.destroy');
        });


        Route::prefix('/agenda')->controller(ScheduleController::class)->group(function () {
            Route::get('/', 'index')->name('admin.schedule.index');
            Route::post('/atualiza', 'update')->name('admin.schedule.update');
        });



        Route::prefix('/dashboard')->controller(\App\Http\Controllers\admin\DashboardController::class)->group(function () {
            Route::get('/', 'index')->name('admin.dashboard');
            Route::get('/chart-data', 'getChartData')->name('admin.dashboard.chart-data');
        });
        Route::prefix('/dispositivo')->controller(DeviceController::class)->group(function () {
            Route::get('/', 'index')->name('admin.device.index');
            Route::get('/novo', 'create')->name('admin.device.create');
            Route::post('/delete', 'delete')->name('admin.device.delete');
            Route::post('/gerarQr', 'gerarQr')->name('dispositivo.gerarQr');
            Route::get('/getDevices', 'getDevices');
            Route::post('/criar', 'store');
            Route::post('/updateStatus', 'updateStatus');
            Route::post('/updateName', 'updateName');
            Route::get('/getStatus', 'getStatus');
        });


        Route::prefix('/clientes')->controller(CustomerController::class)->group(function () {
            Route::get('/', 'index')->name('admin.customer.index');
            Route::get('/novo', 'create')->name('admin.customer.create');
            Route::post('/excluir', 'destroy')->name('admin.customer.delete');
            Route::get('/editar/{id}', 'edit')->name('admin.customer.edit');
            Route::post('/store', 'store')->name('admin.customer.store');
            Route::put('/update/{customer}', 'update')->name('admin.customer.update');
            Route::get('/getCustomers', 'getCustomers');
            Route::get('/buscar-por-telefone', 'buscarPorTelefone')->name('cliente.buscar.telefone');
        });



        Route::prefix('/chat-bot')->controller(ChatBotController::class)->group(function () {
            Route::get('/', 'index')->name('admin.chatbot.index');
            Route::post('/store', 'store')->name('admin.menu-chat-bot.store');
        });

        Route::prefix('/atendimento')->controller(ChatBotController::class)->group(function () {
            Route::get('/', 'index')->name('admin.chat.index');
            Route::post('/up', 'up')->name('admin.chat.up');
            Route::get('/getChats', 'getChats');
        });

        Route::prefix('/pedidos')->controller(OrderController::class)->group(function () {
            Route::get('/', 'index')->name('admin.order.index');
            Route::get('/novo', 'create')->name('admin.order.create');
            Route::get('/getOrders', 'getOrders');
            Route::get('/getOrdersCount', 'getOrdersCount');
            Route::post('/atualizar-status', 'updateStatus');
            Route::post('/atualizar-notify', 'updateNotify')->name('admin.notifications.markAllRead');
            Route::get('/getOrder', 'getOrder');
            Route::post('/calcular-taxa-entrega', 'calcularEntrega');
            Route::post('/novo-pedido', 'storeFromAdmin')->name('admin.pedidos.finalizar');
            Route::get('/motoboys/lista', 'motoboyLista');
            Route::post('/atribuir-motoboy', 'atribuirMotoboy');
            Route::post('/alterar-status', [OrderController::class, 'alterarStatus']);
        });

        Route::prefix('/chat')->controller(ChatBotController::class)->group(function () {
            Route::get('/', 'index')->name('admin.chat.index');
        });



        Route::prefix('/config')->controller(ConfigController::class)->group(function () {
            Route::get('/', 'index')->name('admin.config.index');
            Route::put('/', 'update')->name('admin.config.update');
        });

        Route::prefix('/rota')->controller(RouteController::class)->group(function () {
            Route::get('/', 'index')->name('admin.route.index');
            Route::post('/novo', 'store')->name('admin.route.store');
            Route::delete('/delete', 'delete')->name('admin.route.delete');
            Route::post('/add', 'adicionarColaborador')->name('admin.route.adicionarColaborador');
            Route::get('/edit/{id}', 'edit')->name('admin.route.edit');
        });

        Route::prefix('/mensagem')->controller(MenssageController::class)->group(function () {
            Route::get('/', 'create')->name('admin.message.create');
            Route::get('/agendamentos', 'indexAgendamentos')->name('admin.message.agendamento');
            Route::get('/getAgendamentos', 'getAgendamentos')->name('admin.message.getAgendamento');
            Route::post('/upload', 'upload')->name('upload.imagem');
            Route::post('/countContact', 'countContact');
            Route::get('/getMessage', 'getMessage');
            Route::post('/bulk', 'bulkMessage')->name('admin.message.bulk');;
            Route::get('/relatorio-de-envio', 'index')->name('admin.message.index');;
        });

        Route::prefix('/produtos')->controller(ProductController::class)->group(function () {
            Route::get('/', 'index')->name('admin.product.index');
            Route::post('/store', 'store')->name('admin.product.store');
            Route::post('/storeSistem', 'storeSistem')->name('admin.product.storeSistem');
            Route::get('/novo', 'create')->name('admin.product.create');
            Route::delete('/destroy/{product}', 'destroy')->name('admin.product.destroy');
            Route::put('/destroy/{product}', 'update')->name('admin.product.update');
            Route::get('/edita', 'edit')->name('admin.product.edit');
            Route::get('/buscar-pizza-por-nome', 'buscarPizzaPorNome');
            Route::get('/buscar-produto-por-nome', 'buscarProdutoPorNome');
        });
    });
});


Route::get('/teste', function () {
    // $filePath = base_path('/customers.csv'); // Atualize o caminho para o seu arquivo CSV

    // // dd($filePath);
    // if (file_exists($filePath)) {
    //     $csv = Reader::createFromPath($filePath, 'r');
    //     $csv->setHeaderOffset(0); // Define a primeira linha como cabeçalho

    //     $records = $csv->getRecords();
    //     foreach ($records as $record) {
    //         Customer::create([
    //             'name' => $record['NOME'],
    //             'jid' => $record['FONE1'],
    //             'public_place' => $record['ENDERECO'],
    //             'number' => $record['NUMERO'],
    //             'neighborhood' => $record['BAIRRO'],
    //             'zipcode' => '', // Adicione um valor padrão ou ajuste conforme necessário
    //             'city' => '', // Adicione um valor padrão ou ajuste conforme necessário
    //             'state' => '', // Adicione um valor padrão ou ajuste conforme necessário
    //             'complement' => $record['REFERENCIA'] // Adicione este campo no seu modelo e migração se necessário
    //         ]);
    //     }
    // }

    $service = new \App\Services\DistanceService();

    $address1 = 'Rua José Alves da Silva, 429, Parque Novo Santo Amaro, São Paulo, SP';
    $address2 = 'Rua bonifacio pasquali, 50, São Paulo, SP';

    $distanceKm = $service->getDistanceInKm($address1, $address2);

    echo "Distância: {$distanceKm} km\n";

    $fee = $service->calculateDeliveryFeeAmount($distanceKm);

    echo "Taxa de entrega: R$ {$fee}\n";
});
Route::get('/wh', function () {

    $client = new Client();
    $response = $client->get('https://api.dooki.com.br/v2/sunfit3/webhooks', [
        'headers' => [
            'Authorization' => 'Bearer nWldIVtRLmLY5mYAcIU2sZwQMOJpnFYY87vJu6kJ',
        ],
        'json' => [
            'url'    => 'http://suaurl.com/api/webhooks',
            'events' => ['order.created', 'cart.reminder'],
            'name'   => 'Nome do webhook',
        ],
    ]);

    $data = json_decode($response->getBody(), true);
    Log::info('Dados retornados:', $data);
});

function handle(Request $request)
{
    // Obtenha o corpo da requisição
    $payload = $request->getContent();
    $headers = $request->headers;

    // A chave secreta do Webhook (configure no .env para segurança)
    $webhookSecret = env('YAMPI_WEBHOOK_SECRET');

    // Obtenha a assinatura enviada no header
    $signature = $headers->get('X-Yampi-Hmac-SHA256');

    // Valide a assinatura
    if (!isValidSignature($payload, $signature, $webhookSecret)) {
        return response()->json(['error' => 'Invalid signature'], 403);
    }

    // Processa o evento recebido
    $data = json_decode($payload, true);

    if (!$data || !isset($data['event'])) {
        return response()->json(['error' => 'Invalid payload'], 400);
    }

    // Identifique o evento e processe conforme necessário
    $event = $data['event'];

    switch ($event) {
        case 'order.created':
            Log::info('Pedido criado', $data['resource']);
            break;
        case 'order.paid':
            Log::info('Pedido aprovado', $data['resource']);
            break;
        case 'cart.reminder':
            Log::info('Notificação de carrinho abandonado', $data['resource']);
            break;
        default:
            Log::info('Evento não tratado: ' . $event, $data);
            break;
    }

    return response()->json(['message' => 'Webhook handled successfully'], 200);
}
function isValidSignature($payload, $signature, $secret)
{
    // Gera a assinatura HMAC
    $calculatedSignature = base64_encode(hash_hmac('sha256', $payload, $secret, true));

    // Compare a assinatura recebida com a calculada
    return hash_equals($calculatedSignature, $signature);
}


Route::get('/testeendereco', function () {
    $address1 = '4 rua antigo continente, parque bologne, SP';
    $address2 = '52 estrada da cumbica, cidade ipava, SP';

    $coords1 = getCoordinates($address1);
    $coords2 = getCoordinates($address2);

    if ($coords1 && $coords2) {
        list($distance, $duration) = getDistance($coords1, $coords2);
        return response()->json([
            'distance' => $distance,
            'duration' => $duration,
        ]);
    } else {
        return response()->json(['error' => 'Failed to retrieve coordinates for one or both addresses.'], 400);
    }
});
function getCoordinates($address)
{
    $url = "https://maps.googleapis.com/maps/api/geocode/json";
    $response = Http::get($url, [
        'address' => $address,
        'key' => 'AIzaSyBjtRzX47y95pI2XlmJrsXgka8SHSMLtQw',
    ]);

    $data = $response->json();

    if (!empty($data['results'])) {
        $location = $data['results'][0]['geometry']['location'];
        return [$location['lat'], $location['lng']];
    }

    return null;
}


function getDistance($originCoords, $destinationCoords)
{
    $origins = implode(',', $originCoords);
    $destinations = implode(',', $destinationCoords);

    $url = "https://maps.googleapis.com/maps/api/distancematrix/json";
    $response = Http::get($url, [
        'origins' => $origins,
        'destinations' => $destinations,
        'key' => 'AIzaSyBjtRzX47y95pI2XlmJrsXgka8SHSMLtQw',
    ]);

    $data = $response->json();

    if (!empty($data['rows'][0]['elements'][0]['distance']) && !empty($data['rows'][0]['elements'][0]['duration'])) {
        $distance = $data['rows'][0]['elements'][0]['distance']['text'];
        $duration = $data['rows'][0]['elements'][0]['duration']['text'];
        return [$distance, $duration];
    }

    return [null, null];
}
