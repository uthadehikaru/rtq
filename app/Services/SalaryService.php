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

    public function calculate($salary_id)
    {
        DB::beginTransaction();

        $salary = $this->find($salary_id);

        $presents = Present::whereHas('schedule', function($query) use ($salary){
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
            foreach($presents as $present)
            {
                if($present->status==Present::STATUS_PRESENT)
                    $amount += 10000; // TODO::create config for salary per present
                
                $present->salary_id = $salary->id;
                $present->save();
            }

            SalaryDetail::create([
                'salary_id'=>$salary->id,
                'teacher_id'=>$teacher_id,
                'amount'=>$amount,
            ]);
        }
        
        DB::commit();
    }
}