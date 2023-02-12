<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpdateSchedule extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request,
    \App\Repositories\ScheduleRepository $scheduleRepository,
    $schedule_id)
    {
        $statuses = $request->get('status');
        $descriptions = $request->get('description');
        $schedule = $scheduleRepository->find($schedule_id);

        $schedule->update($request->only(['end_at', 'place']));

        foreach ($schedule->presents as $present) {
            if (isset($statuses[$present->id]) || $present->user_id == Auth::id()) {
                if (isset($statuses[$present->id])) {
                    $present->status = $statuses[$present->id];
                }

                if (isset($descriptions[$present->id])) {
                    $present->description = $descriptions[$present->id];
                }

                if ($present->isDirty()) {
                    $present->save();
                }
            }
        }

        return back()->with('message', 'Berhasil diperbaharui');
    }
}
