<?php

namespace App\Notifications;

use App\Models\Payment;
use App\Services\SettingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class PaymentConfirmation extends Notification implements ShouldQueue
{
    use Queueable;

    private $payment;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title' => 'Konfirmasi Pembayaran terbaru sebesar '.$this->payment->formattedAmount(),
            'created_at' => $this->payment->created_at,
            'url' => route('payments.index'),
        ];
    }

    public function toWhatsapp($notifiable)
    {
        $url = route('payments.confirm', $this->payment->id);
        $details = '';
        foreach ($this->payment->details as $detail) {
            $details .= 'Anggota : '.$detail->member->full_name.' periode '.$detail->period->name."\n";
        }
        return [
            'number' => (new SettingService())->value('whatsapp'),
            'message' => '*Konfirmasi Pembayaran terbaru* '."\n"
            ."Nominal : ".$this->payment->formattedAmount()."\n"
            .$details."\n"
            ."*klik link untuk mengkonfirmasi*\n"
            .$url,
        ];
    }
}
