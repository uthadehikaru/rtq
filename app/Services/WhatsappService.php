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

    public function getQrcode()
    {
        $response = Http::withHeaders(['Authorization' => 'Bearer '.$this->token])->get($this->url.'qr/image');
        $result = $response->json();
        return $result;
    }

    public function logout()
    {
        $response = Http::withHeaders(['Authorization' => 'Bearer '.$this->token])->get($this->url.'qr/logout');
        $result = $response->json();
        return $result;
    }

    public function getQrcodeBase64()
    {
        $response = Http::withHeaders(['Authorization' => 'Bearer '.$this->token])->get($this->url.'qr/image/base64');
        $result = $response->json();
        return $result;
    }

    public function clearAuth()
    {
        $response = Http::withHeaders(['Authorization' => 'Bearer '.$this->token])->get($this->url.'qr/clear-auth');
        $result = $response->json();
        return $result;
    }

    public function regenerateQrcode()
    {
        $response = Http::withHeaders(['Authorization' => 'Bearer '.$this->token])->get($this->url.'qr/generate');
        $result = $response->json();
        return $result;
    }

    public function getStatus()
    {
        $response = Http::withHeaders(['Authorization' => 'Bearer '.$this->token])->get($this->url.'status');
        $result = $response->json();
        return $result;
    }

    public function sendMessage($phone, $message)
    {
        Log::debug('Sending message to '.$phone.' with message '.$message);
        $response = Http::withHeaders(['Authorization' => 'Bearer '.$this->token])->post($this->url.'message', [
            'phoneNumber' => $phone,
            'message' => $message,
        ]);

        $result = $response->json();
        if($result['success'] != true) {
            throw new \Exception($result['message']);
        }else{
            Log::info($result);
        }

        return $result;
    }
    
    public function sendImage($phone, $base64Image, $caption = null)
    {
        Log::debug('Sending image to '.$phone.' with caption '.$caption);
        $response = Http::withHeaders(['Authorization' => 'Bearer '.$this->token])->post($this->url.'image', [
            'phoneNumber' => $phone,
            'file' => $base64Image,
            'caption' => $caption,
        ]);

        $result = $response->json();
        if($result['success'] != true) {
            throw new \Exception($result['message']);
        }else{
            Log::info($result);
        }

        return $result;
    }
}