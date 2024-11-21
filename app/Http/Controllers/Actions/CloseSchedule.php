<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Repositories\ScheduleRepository;
use App\Services\SettingService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class CloseSchedule extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, SettingService $settingService,
        ScheduleRepository $scheduleRepository, $schedule_id)
    {
        $data = $request->validate([
            'lat' => 'required',
            'long' => 'required',
            'photo' => 'required',
        ]);
        try {
            $schedule = Schedule::find($schedule_id);
            $present = $schedule->presents()->where('user_id', Auth::id())->first();
            $batch = $schedule->batch;

            $duration = $settingService->value('durasi_'.str($schedule->batch->course->type)->snake(), 0);
            if (Carbon::now()->diffInMinutes($present->attended_at) < $duration) {
                return response()->json(['error' => 'Belum diperbolehkan absen keluar, minimal jam '.$present->attended_at->addMinutes($duration)->format('H:i')]);
            }

            $file = 'jadwal/'.Auth::user()->name.' '.$batch->name.' '.Carbon::now()->format('d-M-Y H-i').'.jpg';
            Storage::disk('public')->put($file, file_get_contents($data['photo']));
            $image = Image::make(Storage::disk('public')->get($file));
            $image->rectangle(5, 5, 250, 150, function ($draw) {
                $draw->background('rgba(255, 255, 255, 0.5)');
                $draw->border(2, '#000');
            });
            $y = 10;
            $texts = $request->only(['lat', 'long']);
            $texts['Tanggal, jam'] = Carbon::now()->format('d M Y, H:i');
            $texts['Nama'] = Auth::user()->name;
            $texts['Halaqoh'] = $batch->name;
            $texts['Pengganti'] = $present->is_badal ? 'Ya' : 'Tidak';
            $texts['posisi'] = $scheduleRepository->calculateDistance($texts).' meter';
            foreach ($texts as $key => $text) {
                $image->text($key.' : '.$text, 10, $y, function ($font) {
                    $font->file(public_path('assets/fonts/Roboto-Regular.ttf'));
                    $font->size(14);
                    $font->align('left');
                    $font->valign('top');
                });
                $y += 20;
            }
            $image->save(storage_path('app/public/'.$file));

            $present->update(['photo_out' => $file, 'leave_at' => Carbon::now()->format('H:i')]);

            if (! $schedule->end_at) {
                $schedule->update(['end_at' => Carbon::now()->format('H:i')]);
            }

            $result['error'] = null;
            $result['schedule_id'] = $schedule->id;
            $result['path'] = asset('storage/'.$file);

            return response()->json($result);
        } catch (Exception $ex) {
            return response()->json(['error' => $ex->getMessage()]);
        }
    }
}
