<?php

namespace App\Notifications;

use App\Models\Violation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class UserIqobCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public $violation;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Violation $violation)
    {
        $this->violation = $violation;
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
            'title' => 'Iqob : '.$this->violation->description.' Rp. '.money($this->violation->amount),
            'created_at' => $this->violation->created_at,
            'url' => route('member.iqob.index'),
        ];
    }
}
