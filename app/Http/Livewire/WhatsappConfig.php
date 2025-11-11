<?php

namespace App\Http\Livewire;

use App\Services\WhatsappService;
use Livewire\Component;

class WhatsappConfig extends Component
{
    public $qrcode;
    public $status;
    public $service;
    public $whatsapp_url;

    public function mount()
    {
        $this->whatsapp_url = config('app.whatsapp_url');
        $this->getStatus();
    }

    public function getStatus()
    {
        try {
            $result = (new WhatsappService)->getStatus();
            if($result['success']) {
                $this->status = $result['data']['connection']['status'];
                $this->service = $result['data']['service'];
                if($this->status == 'qr_ready') {
                    $this->refreshQrcode();
                }
            } else {
                $this->status = $result['message'];
            }
        } catch (\Exception $e) {
            $this->status = $e->getMessage();
        }
    }

    public function refreshQrcode()
    {
        try {   
            $result = (new WhatsappService)->getQrcodeBase64();
            if($result['success']) {
                $this->qrcode = $result['data']['qrCodeBase64'];
                $this->status = 'Segera Scan QR Code untuk login';
            } else {
                $this->qrcode = null;
                $this->status = $result['message'];
                $this->getStatus();
            }
        } catch (\Exception $e) {
            $this->status = $e->getMessage();
        }
    }

    public function logout()
    {
        try {
            $result = (new WhatsappService)->logout();
            $this->status = $result['data']['message'] ?? 'Logout berhasil';
            $this->getStatus();
        } catch (\Exception $e) {
            $this->status = $e->getMessage();
        }
    }

    public function clear()
    {
        try {
            $result = (new WhatsappService)->clearAuth();
            $result = (new WhatsappService)->regenerateQrcode();
            $this->status = $result['data']['message'];
        } catch (\Exception $e) {
            $this->status = $e->getMessage();
        }
    }
    
    public function render()
    {
        return view('livewire.whatsapp-config')
        ->extends('layouts.app');
    }
}
