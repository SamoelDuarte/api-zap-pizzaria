<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Cliente;
use App\Models\Device;
use App\Models\Messagen;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as GuzzleRequest;

class WebhookController extends Controller
{
    public function evento(Request $request)
    {
        $raw = $request->getContent();
        $data = json_decode($raw, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['erro' => 'JSON inválido'], 400);
        }

        $numeroCompleto = $data['data']['key']['remoteJid'] ?? null;
        if (!$numeroCompleto) {
            return response()->json(['erro' => 'Número não encontrado'], 422);
        }

        // Limpa o número
        $numero = preg_replace('/[^0-9]/', '', $numeroCompleto);
        if (str_starts_with($numero, '55')) {
            $numero = substr($numero, 2);
        }

        $jid = "55{$numero}";

        // Verifica se já existe um chat ativo
        $chat = Chat::where('jid', $jid)->where('active', true)->first();

        if ($chat) {
            Log::info("Chat já ativo para {$jid}, não enviando nova mensagem.");
            return response()->json(['status' => 'Chat já ativo, mensagem não reenviada']);
        }

        // Não existe chat ativo, cria um novo
        $chat = Chat::create([
            'jid' => $jid,
            'active' => true,
            'erro' => 0,
            'flow_stage' => 'aguardando',
            'await_answer' => null,
            'session_id' => Device::where('status', 'open')->first()?->id,
            'service_id' => null,
        ]);

        Log::info("Chat criado para o número {$jid}");


        Log::info("Chat criado ou atualizado para o número 55{$numero}");

        // Envia mensagem com link
        $link = "https://fornadapronta.com.br/checkout/pedido/55{$numero}";
        $mensagem = "🍕 Olá! Que tal fazer seu pedido pelo nosso app? 😄 Acesse agora: $link\n\nEstamos te esperando com muito carinho e sabor! ❤️";

        $device = Device::where('status', "open")->first();
        if (!$device) {
            return response()->json(['erro' => 'Nenhum dispositivo ativo encontrado'], 500);
        }

        $url = "http://147.79.111.119:8080/message/sendText/{$device->session}";
        $headers = [
            'Content-Type' => 'application/json',
            'apikey' => env('TOKEN_EVOLUTION'),
        ];

        $body = json_encode([
            'number' => "55{$numero}",
            'text' => $mensagem,
        ]);

        try {
            $client = new Client();
            $requestGuzzle = new GuzzleRequest('POST', $url, $headers, $body);
            $response = $client->sendAsync($requestGuzzle)->wait();

            $status = $response->getStatusCode();
            $resposta = $response->getBody()->getContents();

            Log::info("Webhook: Mensagem enviada para {$numero} com status {$status}");

            return response()->json([
                'status' => 'Mensagem enviada com sucesso',
                'resposta_api' => json_decode($resposta, true),
            ]);
        } catch (\Exception $e) {
            Log::error("Webhook: Erro ao enviar mensagem: " . $e->getMessage());

            return response()->json([
                'erro' => 'Falha ao enviar mensagem',
                'mensagem' => $e->getMessage(),
            ]);
        }
    }
}
