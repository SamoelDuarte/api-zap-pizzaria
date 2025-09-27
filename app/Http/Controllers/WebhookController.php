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


        $fromMe = $data['data']['key']['fromMe'] ?? null;
        if ($fromMe === true) {
            $numeroCompleto = $data['data']['key']['remoteJid'] ?? null;
            if (!$numeroCompleto) {
                return response()->json(['erro' => 'Número não encontrado'], 422);
            }

            $numero = preg_replace('/[^0-9]/', '', $numeroCompleto);
            if (str_starts_with($numero, '55')) {
                $numero = substr($numero, 2);
            }

            $jid = "55{$numero}";

            // Cria ou atualiza chat com stage "eu_iniciei"
            $chat = Chat::updateOrCreate(
                ['jid' => $jid],
                [
                    'active' => true,
                    'flow_stage' => 'eu_iniciei',
                    'erro' => 0,
                    'await_answer' => null,
                    'session_id' => Device::where('status', 'open')->first()?->id,
                    'service_id' => null,
                ]
            );

            Log::info("Chat criado/iniciado por mim mesmo ({$jid}), sem enviar mensagem.");

            return response()->json(['status' => 'Chat criado a partir de mensagem minha, sem envio de mensagem']);
        }


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
        $agora = \Carbon\Carbon::now();
        if ($agora->hour >= 1 && $agora->hour < 18) {
            Log::info("Mensagem não enviada para {$jid}, horário entre 01:00 e 18:00.");
            return response()->json([
                'status' => 'Fora do horário permitido para envio (somente entre 18:00 e 00:59)',
            ]);
        }
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
        $link = url("checkout/pedido/55{$numero}");
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

    public function deletaChat()
    {
        try {
            // Deleta todos os chats
            $totalChats = Chat::count();
            Chat::truncate(); // Remove todos os registros da tabela
            
            Log::info("Todos os chats foram deletados. Total removido: {$totalChats}");
            
            return response()->json([
                'status' => 'success',
                'message' => 'Todos os chats foram deletados com sucesso',
                'total_deletados' => $totalChats
            ]);
            
        } catch (\Exception $e) {
            Log::error("Erro ao deletar chats: " . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao deletar os chats',
                'erro' => $e->getMessage()
            ], 500);
        }
    }
}
