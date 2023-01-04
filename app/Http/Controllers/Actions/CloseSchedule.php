<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Interfaces\MemberRepositoryInterface;
use App\Models\Schedule;
use App\Repositories\ScheduleRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CloseSchedule extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke($schedule_id)
    {
        $closeTime = Carbon::now()->format('H:i');
        Schedule::find($schedule_id)->update(['end_at'=>$closeTime]);

        return back()->with('message', __('Kelas telah ditutup pada '.$closeTime));
    }
}
