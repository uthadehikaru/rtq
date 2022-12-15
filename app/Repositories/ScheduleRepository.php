<?php

namespace App\Repositories;

use App\Interfaces\ScheduleRepositoryInterface;
use App\Models\Present;
use App\Models\Schedule;
use App\Models\Teacher;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScheduleRepository implements ScheduleRepositoryInterface
{
    public function all()
    {
        return Schedule::with('batch', 'batch.course')
        ->withCount('presents')
        ->latest('scheduled_at')
        ->get();
    }

    public function count()
    {
        return Schedule::count();
    }

    public function getLatest($limit = 10)
    {
        return Schedule::latest()->paginate($limit);
    }

    public function find($id)
    {
        return Schedule::with('presents', 'presents.user')->findOrFail($id);
    }

    public function delete($id)
    {
        Schedule::destroy($id);
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        $schedule = Schedule::with('batch')->whereDate('scheduled_at', Carbon::parse($data['scheduled_at'])->startOfDay())
        ->where('batch_id',$data['batch_id'])
        ->first();
        
        if($schedule)
            throw new Exception("Jadwal halaqoh ".$schedule->batch->name." untuk tanggal ".$schedule->scheduled_at->format('d M Y')." sudah ada");
        
        $schedule = Schedule::create($data);

        Present::create([
            'schedule_id' => $schedule->id,
            'user_id' => Auth::id(),
            'status' => 'present',
            'type'=>'teacher',
            'attended_at'=>Carbon::now()->format('H:i'),
            'is_badal'=>$data['is_badal'],
        ]);

        foreach ($schedule->batch->members as $member) {
            Present::create([
                'schedule_id' => $schedule->id,
                'user_id' => $member->user_id,
                'status' => 'absent',
                'type'=>'member',
            ]);
        } 

        DB::commit();

        return $schedule;
    }

    public function update($id, array $data)
    {
        return Schedule::whereId($id)->update($data);
    }

    public function getByTeacher($user_id)
    {
        return Schedule::with('batch')
            ->withCount(['presents'=>function($query){
                $query->where('type','member');
            }])
            ->whereRelation('presents','user_id', $user_id)
            ->latest('scheduled_at')
            ->paginate(20);
    }
}
