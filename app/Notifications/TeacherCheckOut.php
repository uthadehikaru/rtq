<?php

namespace App\Notifications;

use App\Models\Present;
use App\Services\SettingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class TeacherCheckOut extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Present $present)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['whatsapp'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $present = $this->present;
        
        $mimeType = Storage::disk('public')->mimeType($present->photo_out);
        $image = 'data:'.$mimeType.';base64,'.base64_encode(Storage::disk('public')->get($present->photo_out));
        
        $caption = 'Bukti Absen Keluar '.$present->user->name. ' - '.$present->schedule->batch->name .' - '.$present->schedule->scheduled_at->format('d-M-Y') .' - '.$present->leave_at->format('H:i');
        $number = (new SettingService())->value('whatsapp');
        $message = "*Absen keluar hari ini*"."\n"
        .'Pengajar : '.$present->user->name."\n"
        .'Halaqoh : '.$present->schedule->batch->name."\n"
        .'Jadwal : '.$present->schedule->scheduled_at->format('d-M-Y H:i')."\n"
        .'Jam Masuk : '.$present->attended_at->format('H:i')."\n"
        .'Jam Keluar : '.$present->leave_at->format('H:i')."\n"
        ."\n"
        .'*Dikirim otomatis oleh sistem RTQ*';
        return [
            'number' => $number,
            'message' => $message,
            'image' => $image,
            'caption' => $caption,
        ];
    }
}
