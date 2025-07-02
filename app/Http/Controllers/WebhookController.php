<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Device;
use App\Models\Messagen;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function evento(Request $request)
    {
        // Captura o JSON bruto e decodifica
        $raw = $request->getContent();
        $data = json_decode($raw, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['erro' => 'JSON inválido'], 400);
        }

        // Extrai número do cliente
        $numeroCompleto = $data['data']['key']['remoteJid'] ?? null;
        if (!$numeroCompleto) {
            return response()->json(['erro' => 'Número não encontrado'], 422);
        }

        $numero = preg_replace('/[^0-9]/', '', $numeroCompleto);
        if (str_starts_with($numero, '55')) {
            $numero = substr($numero, 2);
        }

        // Monta o link e mensagem
        $link = "https://fornadapronta.com.br/pedido/{$numero}";
        $mensagem = "🍕 Olá! Que tal fazer seu pedido pelo nosso app? 😄 Acesse agora: $link\n\nEstamos te esperando com muito carinho e sabor! ❤️";

        // Pega sessão ativa do device
        $device = Device::where('ativo', 1)->first();
        if (!$device) {
            return response()->json(['erro' => 'Nenhum dispositivo ativo encontrado'], 500);
        }

        // Prepara envio
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
