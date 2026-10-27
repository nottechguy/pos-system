<?php

namespace App\Services;

use App\Models\Promotion;
use Illuminate\Support\Facades\Log;

class PromotionNotificationService
{
    /**
     * Broadcasts a notification to all active users when a promotion is created or about to end.
     */
    public function sendPromotionNotification(Promotion $promotion, string $event = 'created'): void
    {
        $title = $event === 'created' ? '¡Nueva Promoción Disponible!' : '¡La promoción está por finalizar!';
        $body = "{$promotion->name} - Aprovecha precios especiales en productos seleccionados.";

        // --- FUTURE FCM INTEGRATION STUB ---
        // $messaging = app('firebase.messaging');
        // $message = CloudMessage::withTarget('topic', 'all-users')
        //     ->withNotification(Notification::create($title, $body));
        // $messaging->send($message);
        // ------------------------------------

        Log::info("FCM Notification Simulation [{$event}]: {$title} - {$body}", [
            'promotion_id' => $promotion->id
        ]);
    }
}