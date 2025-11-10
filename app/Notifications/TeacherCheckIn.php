<?php

namespace App\Notifications;

use App\Models\Schedule;
use App\Repositories\PresentRepository;
use App\Services\SettingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class TeacherCheckIn extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Schedule $schedule)
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
        $presentRepository = new PresentRepository();
        $present = $presentRepository->getPresentByUser($this->schedule->id, $notifiable->id);
        
        $mimeType = Storage::disk('public')->mimeType($present->photo);
        $image = 'data:'.$mimeType.';base64,'.base64_encode(Storage::disk('public')->get($present->photo));
        
        $caption = 'Bukti Absen Masuk '.$present->user->name. ' - '.$present->schedule->batch->name .' - '.$present->schedule->scheduled_at->format('d-M-Y') .' - '.$present->attended_at->format('H:i');
        $number = (new SettingService())->value('whatsapp');
        $message = "*Absen masuk hari ini*"."\n"
        .'Pengajar : '.$present->user->name."\n"
        .'Halaqoh : '.$present->schedule->batch->name."\n"
        .'Jadwal : '.$present->schedule->scheduled_at->format('d-M-Y H:i')."\n"
        .'Jam Masuk : '.$present->attended_at->format('H:i')."\n"
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
