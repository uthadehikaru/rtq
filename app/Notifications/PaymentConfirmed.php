<?php

namespace App\Notifications;

use App\Models\PaymentDetail;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class PaymentConfirmed extends Notification implements ShouldQueue
{
    use Queueable;

    private $paymentDetail;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(PaymentDetail $paymentDetail)
    {
        $this->paymentDetail = $paymentDetail;
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
            'title' => 'Pembayaran periode '.$this->paymentDetail->period->name.' telah dikonfirmasi',
            'created_at' => Carbon::now(),
            'url' => route('dashboard'),
        ];
    }
}
