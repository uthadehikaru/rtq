<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Repositories\ScheduleRepository;
use Exception;
use Illuminate\Http\Request;

class CreateSchedule extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request,
    ScheduleRepository $scheduleRepository)
    {
        $request->validate([
            'batch_id' => 'required',
            'badal' => 'required',
        ]);

        try {
            $data = [
                'batch_id' => $request->get('batch_id'),
                'is_badal' => $request->get('badal'),
            ];
            $schedule = $scheduleRepository->createByTeacher($data);

            if ($schedule) {
                return to_route('teacher.schedules.detail', $schedule->id)->with('message', 'Jadwal berhasil disimpan');
            }

            return back()->with('error', 'gagal membuat jadwal');
        } catch(Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }
    }
}
