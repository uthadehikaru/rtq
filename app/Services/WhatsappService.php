<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class WhatsappService
{
    private $url;
    private $token;

    public function __construct()
    {
        $this->url = config('app.whatsapp_url');
        $this->token = config('app.whatsapp_token');
    }

    public function sendMessage($phone, $message)
    {
        Log::debug('Sending message to '.$phone.' with message '.$message);
        $response = Http::withHeaders(['Authorization' => 'Bearer '.$this->token])->post($this->url.'message', [
            'phoneNumber' => $phone,
            'message' => $message,
        ]);

        Log::info($response->json());
        return $response->json();
    }
    
    public function sendImage($phone, $base64Image, $caption = null)
    {
        Log::debug('Sending image to '.$phone.' with caption '.$caption);
        $response = Http::withHeaders(['Authorization' => 'Bearer '.$this->token])->post($this->url.'image', [
            'phoneNumber' => $phone,
            'file' => $base64Image,
            'caption' => $caption,
        ]);

        Log::info($response->json());
        return $response->json();
    }
}