<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Messagen;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function evento(Request $request)
    {
        // Captura o corpo cru da requisição
        $raw = $request->getContent();

        // Decodifica o JSON
        $data = json_decode($raw, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['erro' => 'JSON inválido'], 400);
        }

        // Extrai dados principais
        $numeroCompleto = $data['data']['key']['remoteJid'] ?? null;

        if (!$numeroCompleto) {
            return response()->json(['erro' => 'Número não encontrado'], 422);
        }

        // Limpa o número (ex: "5511986123660@s.whatsapp.net" => "11986123660")
        $numero = preg_replace('/[^0-9]/', '', $numeroCompleto);
        if (str_starts_with($numero, '55')) {
            $numero = substr($numero, 2);
        }

        // Cria link personalizado
        $link = "https://fornadapronta.com.br/pedido/" . $numero;

        // Monta mensagem simpática com emojis
        $mensagem = "🍕 Olá! Que tal fazer seu pedido pelo nosso app? 😄 Acesse agora: $link\n\nEstamos te esperando com muito carinho e sabor! ❤️";

        // Simula envio da mensagem de volta para o WhatsApp (você adapta conforme seu sistema)
        $response = Http::post('http://147.79.111.119:8080/send-message', [
            'apikey' =>  env('TOKEN_EVOLUTION'),
            'number' => "55$numero",
            'message' => $mensagem,
        ]);

        if ($response->failed()) {
            // Opcional: loga a resposta com detalhes
            Log::error('Erro ao enviar mensagem WhatsApp', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            // Retorna erro com o conteúdo recebido da API
            return response()->json([
                'erro' => 'Falha ao enviar mensagem',
                'status_code' => $response->status(),
                'resposta' => $response->body(),
            ], 200); // Use 200 se quiser ver no navegador/ferramenta de webhook
        }


        return response()->json(['status' => 'Mensagem enviada com sucesso']);
    }
}
