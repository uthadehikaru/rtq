<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UpdateSchedule extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request,
    \App\Interfaces\ScheduleRepositoryInterface $scheduleRepository,
    $schedule_id)
    {
        $statuses = $request->get('status');
        $descriptions = $request->get('description');
        $attended_ats = $request->get('attended_at');
        $schedule = $scheduleRepository->find($schedule_id);

        $schedule->update($request->only(['end_at', 'place']));

        foreach ($schedule->presents as $present) {
            if (isset($statuses[$present->id])) {
                $status = $statuses[$present->id];
                $present->status = $status;
                $present->description = $descriptions[$present->id];
                if ($present->isDirty()) {
                    $present->save();
                }
            }
        }

        return back()->with('message', 'Berhasil diperbaharui');
    }
}
