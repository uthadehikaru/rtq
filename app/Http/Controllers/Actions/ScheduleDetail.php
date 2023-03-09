<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Models\Present;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScheduleDetail extends Controller
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
        $schedule = $scheduleRepository->find($schedule_id);
        $data['schedule'] = $schedule;
        $data['users'] = User::with('member', 'member.batches')
            ->role('member')
            ->whereHas('member.batches')
            ->whereNotExists(function ($query) use ($schedule_id) {
                $query->select(DB::raw('1'))
                ->from('presents')
                ->whereColumn('users.id', 'presents.user_id')
                ->where('presents.schedule_id', $schedule_id);
            })
            ->orderBy('name')
            ->get();
        $data['statuses'] = Present::STATUSES;
        $data['teacherPresent'] = $schedule->presents->where('user_id', Auth::id())->latest()->first();
        $data['canUpdate'] = true;

        return view('forms.schedule-detail', $data);
    }
}
