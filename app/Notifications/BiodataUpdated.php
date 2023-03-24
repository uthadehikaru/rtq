<?php

namespace App\Notifications;

use App\Models\Member;
use App\Models\Payment;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class BiodataUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    private $member,$biodata;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Member $member, Setting $biodata)
    {
        $this->member = $member;
        $this->biodata = $biodata;
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
            'title' => 'Pembaharuan Biodata '.$this->member->full_name.'. menunggu konfirmasi admin',
            'created_at' => $this->biodata->created_at,
            'url' => route('biodata.index'),
        ];
    }
}
