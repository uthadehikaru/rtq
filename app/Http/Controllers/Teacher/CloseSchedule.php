<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Services\SettingService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CloseSchedule extends Controller
{
    public function __invoke(SettingService $settingService, $schedule_id)
    {
        $schedule = Schedule::with(['batch', 'batch.course'])->findOrFail($schedule_id);
        $present = $schedule->presents()->where('user_id', Auth::id())->first();
        if ($present->leave_at) {
            return back()->with('error', 'Anda sudah absen keluar pada '.$present->leave_at->format('H:i'));
        }

        $duration = $settingService->value('durasi_'.str($schedule->batch->course->type)->snake(), 0);
        if (Carbon::now()->diffInMinutes($present->attended_at) < $duration) {
            return back()->with('error', 'Belum diperbolehkan absen keluar, minimal jam '.$present->attended_at->addMinutes($duration)->format('H:i'));
        }
        $data['duration'] = $duration;
        $data['attended_at'] = $present->attended_at;
        // dd($data);
        $data['title'] = 'Tutup Kelas '.$schedule->batch->name;
        $data['schedule'] = $schedule;
        $data['present'] = $schedule->presents()->where('user_id', Auth::id())->first();

        return view('schedule-close', $data);
    }
}
