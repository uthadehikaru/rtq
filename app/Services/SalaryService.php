<?php

namespace App\Services;

use App\models\Present;
use App\Models\Salary;
use App\Models\SalaryDetail;
use App\models\Schedule;
use DB;

class SalaryService
{
    public function all()
    {
        return Salary::all();
    }

    public function store($data)
    {
        return Salary::create($data);
    }

    public function update($id, $data)
    {
        return Salary::find($id)->update($data);
    }

    public function delete($id)
    {
        return Salary::find($id)->delete();
    }

    public function find($id)
    {
        return Salary::find($id);
    }

    public function findDetails($id)
    {
        return Salary::with('details','details.teacher')->find($id);
    }

    public function findDetail($id)
    {
        return SalaryDetail::with('teacher','salary')->find($id);
    }

    public function deleteDetail($id)
    {
        $detail = SalaryDetail::find($id);
        Present::where('salary_id',$detail->salary_id)
        ->where('teacher_id',$detail->teacher_id)
        ->update(['salary_id'=>null]);

        return $detail->delete();
    }

    public function calculate($salary_id)
    {
        DB::beginTransaction();

        $salary = $this->find($salary_id);

        $presents = Present::with('schedule','schedule.batch','schedule.batch.course')
        ->whereHas('schedule', function($query) use ($salary){
            return $query
            ->where('scheduled_at','>',$salary->start_date)
            ->where('scheduled_at','<',$salary->end_date);
        })->whereNotNull('teacher_id')
        ->get();

        $teacherPresents = $presents->groupBy(function ($present, $key) {
            return $present->teacher_id;
        });

        foreach($teacherPresents as $teacher_id=>$presents)
        {
            $amount = 0;
            $summary = [
                'own'=>0,
                'switch'=>0,
                'present'=>0,
                'late'=>0,
                'absent'=>0,
                'permit'=>0,
            ];

            foreach($presents as $present)
            {
                if($present->status==Present::STATUS_PRESENT){
                    $amount += 10000; // TODO::create config for salary per present
                    $summary[Present::STATUS_PRESENT]++;
                    $scheduled_at = $present->schedule->scheduled_at;
                    $attended_at = $present->schedule->scheduled_at;
                    $attended_at->setTimeFromTimeString($present->attended_at->format('H:i:s'));
                    $diff = $attended_at->diffInMinutes($scheduled_at);
                    if($diff>15) // TODO::create config for late time
                        $summary['late']++;
                }elseif($present->status==Present::STATUS_ABSENT && $present->description){
                    $summary['permit']++;
                }else{
                    $summary[Present::STATUS_ABSENT]++;
                }

                if($present->schedule->batch->teacher_id==$teacher_id)
                    $summary['own']++;
                else
                    $summary['switch']++;

                
                $present->salary_id = $salary->id;
                $present->save();
            }

            SalaryDetail::updateOrCreate([
                'salary_id'=>$salary->id,
                'teacher_id'=>$teacher_id,
            ],[
                'amount'=>$amount,
                'summary'=>$summary,
            ]);
        }
        
        DB::commit();
    }

    public function getPresentOfSalary($salary_id)
    {
        return Present::with('teacher','schedule')
        ->where('salary_id',$salary_id)
        ->whereNotNull('teacher_id')
        ->orderBy('teacher_id')
        ->latest()
        ->get()
        ->groupBy(function ($present, $key) {
            return $present->teacher_id;
        });
    }
}