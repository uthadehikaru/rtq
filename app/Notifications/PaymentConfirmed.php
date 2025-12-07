<?php

namespace App\Notifications;

use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class PaymentConfirmed extends Notification implements ShouldQueue
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
            'title' => 'Pembayaran pada tanggal '.$this->payment->created_at->format('d M Y').' telah dikonfirmasi',
            'created_at' => Carbon::now(),
            'url' => route('member.payments.index'),
        ];
    }

    public function toWhatsapp($notifiable)
    {
        $details = '';
        foreach ($this->payment->details as $detail) {
            if ($detail->member->phone !== $notifiable->member?->phone) {
                continue;
            }
            $details .= 'Anggota : '.$detail->member->full_name.' periode '.$detail->period->name."\n";
        }
        return [
            'number' => $notifiable->member?->phone,
            'message' => 'Terima kasih, pembayaran SPP '.config('app.name').' pada tanggal '.$this->payment->created_at->format('d M Y').' via '.$this->payment->payment_method.' telah dikonfirmasi'."\n"
            .$details."\n"
            ."cek pembayaran di : ".route('member.payments.index')."\n\n"
            ."*pesan ini dikirim otomatis oleh sistem ".config('app.name')."*",
        ];
    }
}
