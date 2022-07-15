<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Present;

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
        $data['schedule'] = $scheduleRepository->find($schedule_id);
        $data['statuses'] = Present::STATUSES;
        return view('forms.schedule-detail', $data);
    }
}
