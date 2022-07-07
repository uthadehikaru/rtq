<?php

namespace App\Repositories;

use App\Interfaces\ScheduleRepositoryInterface;
use App\Models\Schedule;
use App\Models\Present;

class ScheduleRepository implements ScheduleRepositoryInterface 
{

    public function all() 
    {
        return Schedule::with('batch','teacher','batch.course','batch.teacher')
        ->withCount('presents')
        ->get();
    }

    public function count() 
    {
        return Schedule::count();
    }
    
    public function getLatest($limit=10) 
    {
        return Schedule::latest()->paginate($limit);
    }

    public function find($id) 
    {
        return Schedule::findOrFail($id);
    }

    public function delete($id) 
    {
        Schedule::destroy($id);
    }

    public function create(array $data) 
    {
        $schedule = Schedule::create($data);

        Present::updateOrCreate([
            'schedule_id'=>$schedule->id,
            'teacher_id'=>$schedule->batch->teacher_id,
        ],[
            'status'=>'absent',
        ]);

        if($schedule->teacher_id){
            Present::updateOrCreate([
                'schedule_id'=>$schedule->id,
                'teacher_id'=>$schedule->teacher_id,
            ],[
                'status'=>'absent',
            ]);
        }

        foreach($schedule->batch->members as $member){
            Present::create([
                'schedule_id'=>$schedule->id,
                'member_id'=>$member->id,
            ]);
        }

        return $schedule;
    }

    public function update($id, array $data) 
    {
        return Schedule::whereId($id)->update($data);
    }
}