<?php

namespace App\Services;

use App\Models\Present;
use App\Models\Salary;
use App\Models\SalaryDetail;
use App\Models\Setting;
use App\Models\Teacher;
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
        return Salary::with('details', 'details.user')->find($id);
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
            ->where('scheduled_at', '>=', $salary->start_date)
            ->where('scheduled_at', '<=', $salary->end_date);
        })->where('type', 'teacher')
        ->get();

        $teacherPresents = $presents->groupBy(function ($present, $key) {
            return $present->user_id;
        });

        foreach ($teacherPresents as $user_id => $presents) {
            $amount = 0;
            $summary = [
                'tahsin_anak'=>['total'=>0,'amount'=>0],
                'tahsin_dewasa'=>['total'=>0,'amount'=>0],
                'tahsin_balita'=>['total'=>0,'amount'=>0],
                'talaqqi_jamai'=>['total'=>0,'amount'=>0],
                'own' => 0,
                'switch' => 0,
                'present' => 0,
                'late' => 0,
                'late_with_confirm' => 0,
                'late_without_confirm' => 0,
                'absent' => 0,
                'permit' => 0,
                'sick'=>0,
                'base' => 0,
                'oper_santri' => 0,
                'transportasi' => 0,
                'tunjangan' => 0,
                'potongan_telat' => 0,
                'maks_waktu_telat' => setting('maks_waktu_telat'),
            ];

            foreach ($presents as $present) {
                if ($present->status == Present::STATUS_PRESENT) {
                    $type = Str::snake($present->schedule->batch->course->type);
                    $summary[$type]['total']++;
                    $summary[$type]['amount'] += $settings[$type];
                    $summary['base'] += $settings[$type];
                    $amount += $settings[$type];
                    $summary[Present::STATUS_PRESENT]++;
                    $attended_at = $present->attended_at;
                    if(!$attended_at)
                        $attended_at = $present->created_at;
                    if($attended_at->greaterThan($present->schedule->start_at)){
                        $diff = $attended_at->diffInMinutes($present->schedule->start_at);
                        if ($diff > $summary['maks_waktu_telat']) { 
                            $summary['late']++;
                            if($present->description)
                                $summary['late_with_confirm']++;
                            else
                                $summary['late_without_confirm']++;
                        }
                    }

                    $summary['oper_santri'] += Present::where([
                        'schedule_id'=>$present->schedule_id,
                        'type'=>'member',
                        'status'=>'present',
                        'is_transfer'=>true,
                    ])
                    ->count();
                } elseif ($present->status == Present::STATUS_permit) {
                    $summary['permit']++;
                } elseif ($present->status == Present::STATUS_SICK) {
                    $summary['sick']++;
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

            $summary['transportasi'] = $summary['present']*$settings['transportasi'];
            $amount += $summary['transportasi'];

            $summary['nominal_oper'] = $summary['oper_santri']*$settings['oper_santri'];
            $amount += $summary['nominal_oper'];

            $late = $summary['late']-($summary['late_with_confirm']>$settings['maks_telat_dengan_konfirmasi']?3:$summary['late_with_confirm']);
            $summary['potongan_telat'] = $late*$settings['telat_tanpa_konfirmasi'];
            $amount -= $summary['potongan_telat'];

            if ($summary['present'] > 0) {
                $summary['tunjangan'] = $settings['tunjangan'];
                if($summary['permit']>$settings['maks_izin'])
                    $summary['tunjangan'] = 0;
                else
                    $summary['tunjangan'] -= $summary['permit']*$settings['pengurangan_tunjangan_per_izin'];

                $amount += $summary['tunjangan'];
            }

            SalaryDetail::updateOrCreate([
                'salary_id' => $salary->id,
                'user_id' => $user_id,
            ], [
                'amount' => $amount,
                'summary' => $summary,
            ]);
        }

        DB::commit();
    }

    public function getPresentOfSalary($salary_id, $user_id = 0)
    {
        $presents = Present::with(['user', 'schedule','schedule.batch','schedule.batch.course'])
        ->where('salary_id', $salary_id)
        ->where('type', 'teacher')
        ->when($user_id > 0, function ($query) use ($user_id) {
            return $query->where('user_id', $user_id);
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
        ->whereHas('salary', function($query){
            $query->whereNotNull('approved_at');
        })
        ->where('user_id', $teacher_id)
        ->latest()
        ->get();
    }
}
