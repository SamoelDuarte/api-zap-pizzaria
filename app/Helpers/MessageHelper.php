<?php

namespace App\Helpers;

use App\Models\Device;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use Illuminate\Support\Facades\Log;

class MessageHelper
{
    public static function enviarMensagem($numero, $mensagem)
    {
        $numero = preg_replace('/[^0-9]/', '', $numero);

        if (str_starts_with($numero, '55')) {
            $numero = substr($numero, 2);
        }

        // Pega dispositivo ativo
        $device = Device::where('status', "open")->first();
        if (!$device) {
            Log::error('Mensagem: Nenhum dispositivo ativo');
            return false;
        }

        $client = new Client();
        $url = "http://147.79.111.119:8080/message/sendText/{$device->session}";

        $headers = [
            'Content-Type' => 'application/json',
            'apikey' => env('TOKEN_EVOLUTION'),
        ];

        $body = json_encode([
            'number' => '55' . $numero,
            'text' => $mensagem,
        ]);

        try {
            $request = new GuzzleRequest('POST', $url, $headers, $body);
            $response = $client->sendAsync($request)->wait();

            Log::info("Mensagem enviada para 55{$numero}");

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Erro ao enviar mensagem: " . $e->getMessage());
            return false;
        }
    }
}
