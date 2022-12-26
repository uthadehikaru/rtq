<?php

namespace App\Services;

use App\Models\Present;
use App\Models\Salary;
use App\Models\SalaryDetail;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
        return Salary::with('details', 'details.teacher')->find($id);
    }

    public function findDetail($id)
    {
        return SalaryDetail::with('teacher', 'salary')->find($id);
    }

    public function deleteDetail($id)
    {
        $detail = SalaryDetail::find($id);
        Present::where('salary_id', $detail->salary_id)
        ->where('teacher_id', $detail->teacher_id)
        ->update(['salary_id' => null]);

        return $detail->delete();
    }

    public function calculate($salary_id)
    {
        DB::beginTransaction();

        $settings = Setting::group('salary')->pluck('payload', 'name');

        $salary = $this->find($salary_id);

        $presents = Present::with('schedule', 'schedule.batch', 'schedule.batch.course')
        ->whereHas('schedule', function ($query) use ($salary) {
            return $query
            ->where('scheduled_at', '>', $salary->start_date)
            ->where('scheduled_at', '<', $salary->end_date);
        })->where('type', 'teacher')
        ->get();

        $teacherPresents = $presents->groupBy(function ($present, $key) {
            return $present->user_id;
        });

        foreach ($teacherPresents as $user_id => $presents) {
            $amount = 0;
            $summary = [
                'own' => 0,
                'switch' => 0,
                'present' => 0,
                'late' => 0,
                'absent' => 0,
                'permit' => 0,
                'base' => 0,
                'oper_santri' => 0,
                'transportasi' => 0,
                'tunjangan' => 0,
                'potongan_telat' => 0,
            ];

            foreach ($presents as $present) {
                if ($present->status == Present::STATUS_PRESENT) {
                    $summary['base'] += $settings[Str::snake($present->schedule->batch->course->type)];
                    $amount += $settings[Str::snake($present->schedule->batch->course->type)]; // TODO::create config for salary per present
                    $summary[Present::STATUS_PRESENT]++;
                    $scheduled_at = $present->schedule->scheduled_at;
                    $attended_at = $present->schedule->scheduled_at;
                    $attended_at->setTimeFromTimeString($present->attended_at->format('H:i:s'));
                    $diff = $attended_at->diffInMinutes($scheduled_at);
                    $summary['transportasi'] = $settings['transportasi'];
                    $amount += $summary['transportasi'];
                    if ($diff > 15) { // TODO::create config for late time
                        $summary['late']++;
                    }
                } elseif (in_array($present->status, [Present::STATUS_SICK, Present::STATUS_permit])) {
                    $summary['permit']++;
                } else {
                    $summary[Present::STATUS_ABSENT]++;
                }

                if ($present->is_badal) {
                    $summary['switch']++;
                }

                $summary['own']++;

                $present->salary_id = $salary->id;
                $present->save();
            }

            if ($summary['present'] > 0) {
                $summary['tunjangan'] = $settings['tunjangan'];
                $amount += $summary['tunjangan'];
            }

            SalaryDetail::updateOrCreate([
                'salary_id' => $salary->id,
                'teacher_id' => $user_id,
            ], [
                'amount' => $amount,
                'summary' => $summary,
            ]);
        }

        DB::commit();
    }

    public function getPresentOfSalary($salary_id, $teacher_id = 0)
    {
        $presents = Present::with('user', 'schedule')
        ->where('salary_id', $salary_id)
        ->where('type', 'teacher')
        ->when($teacher_id > 0, function ($query) use ($teacher_id) {
            return $query->where('user_id', $teacher_id);
        })
        ->orderBy('user_id')
        ->latest()
        ->get()
        ->groupBy(function ($present, $key) {
            return $present->user_id;
        });

        return $presents;
    }

    public function getTeacherSalaries($teacher_id)
    {
        return SalaryDetail::with('salary')
        ->where('teacher_id', $teacher_id)
        ->latest()
        ->get();
    }
}
