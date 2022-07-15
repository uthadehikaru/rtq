<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Models\Present;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;

class ScheduleDetail extends Controller
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
        $schedule = $scheduleRepository->find($schedule_id);
        $data['schedule'] = $schedule;
        $data['statuses'] = Present::STATUSES;
        $data['teacherPresent'] = $schedule->presents->where('teacher_id',Auth::user()->teacher->id)->first();
        $data['canUpdate'] = Carbon::now()->between($schedule->scheduled_at, $schedule->scheduled_at->addDay());
        return view('forms.schedule-detail', $data);
    }
}
