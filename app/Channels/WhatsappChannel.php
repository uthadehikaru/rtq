<?php

namespace App\Channels;

use App\Notifications\Messages\WhatsappMessage;
use App\Services\WhatsappService;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
        $data = [];
        if(method_exists($notification, 'toWhatsapp')) {
            $data = $notification->toWhatsapp($notifiable);
        }else{
            $data = $notification->toArray($notifiable);
        }
        $phone = $data['number'];
        if(!$phone) {
            Log::warning('No phone number found for '. $notifiable->email);
            return;
        }

        if(Str::prefix($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        }

        // Send the message
        if(isset($data['message'])) {
            $this->whatsappService->sendMessage($phone, $data['message']);
        }
        
        if(isset($data['image'])) {
            $this->whatsappService->sendImage($phone, $data['image'], $data['caption'] ?? null);
        }
    }
}

