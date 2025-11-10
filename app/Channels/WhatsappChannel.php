<?php

namespace App\Channels;

use App\Notifications\Messages\WhatsappMessage;
use App\Services\WhatsappService;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class WhatsappChannel
{
    protected $whatsappService;

    public function __construct(WhatsappService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        try {
            $data = $notification->toArray($notifiable);
            $phone = $data['number'];
            $message = $data['message'];

            // Send the message
            $this->whatsappService->sendMessage($phone, $message);
            
            if(isset($data['image'])) {
                $this->whatsappService->sendImage($phone, $data['image'], $data['caption'] ?? null);
            }
        } catch (\Exception $e) {
            Log::error('Error sending WhatsApp message: '.$e->getMessage());
        }
    }
}

