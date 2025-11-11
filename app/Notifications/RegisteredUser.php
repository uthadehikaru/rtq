<?php

namespace App\Notifications;

use App\Models\Registration;
use App\Services\SettingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class RegisteredUser extends Notification implements ShouldQueue
{
    use Queueable;

    private $registration;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Registration $registration)
    {
        $this->registration = $registration;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'whatsapp'];
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
            'title' => $this->registration->full_name.' mendaftar tahsin '.$this->registration->type,
            'created_at' => $this->registration->created_at,
            'url' => route('registrations.show', $this->registration->id),
        ];
    }

    public function toWhatsapp($notifiable)
    {
        $url = route('registrations.show', $this->registration->id);
        return [
            'number' => (new SettingService())->value('whatsapp'),
            'message' => $this->registration->full_name.' mendaftar tahsin '.$this->registration->type. "\n"
            ."*klik link untuk melihat detail pendaftaran*\n"
            .$url,
        ];
    }
}
