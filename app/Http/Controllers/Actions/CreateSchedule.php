<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Interfaces\ScheduleRepositoryInterface;
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
    ScheduleRepositoryInterface $scheduleRepository)
    {
        $request->validate([
            'scheduled_at' => 'required',
            'batch_id' => 'required',
            'teacher_id' => '',
        ]);

        $schedule = $request->all();
        $scheduleRepository->create($schedule);

        return back()->with('message', 'Jadwal berhasil disimpan');
    }
}
