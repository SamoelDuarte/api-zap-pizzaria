<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FirebaseNotificationHelper
{
    public static function sendToMany(array $tokens, string $title, string $body, array $data = []): void
    {
       $factory = (new \Kreait\Firebase\Factory)
    ->withServiceAccount(base_path('config/firebase/firebase_credentials.json'));
        $messaging = $factory->createMessaging();

        $notification = Notification::create($title, $body);

       $message = CloudMessage::new()
    ->withNotification(Notification::create($title, $body))
    ->withData([
        'title' => $title,
        'body' => $body,
    ]);

        // Envia a mesma mensagem para todos os tokens
        $report = $messaging->sendMulticast($message, $tokens);

        // (Opcional) Exibe quantos envios foram bem-sucedidos
        Log::info("Notificações enviadas com sucesso: {$report->successes()->count()}");
        Log::info("Falhas no envio: {$report->failures()->count()}");
    }
}
