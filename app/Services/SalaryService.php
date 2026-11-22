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
    /**
     * Default operan santri amount per course type (per transferred member).
     * Course types not listed here use the generic `oper_santri` setting.
     */
    public const OPER_SANTRI_DEFAULTS = [
        'tahsin_anak' => 10000,
        'tahsin_dewasa' => 12000,
        'tahsin_balita' => 10000,
    ];

    public static function operSantriKey($type)
    {
        return 'oper_santri_'.$type;
    }

    public function getOperSantriRate($type, $settings)
    {
        if (array_key_exists($type, self::OPER_SANTRI_DEFAULTS)) {
            return $settings[self::operSantriKey($type)] ?? self::OPER_SANTRI_DEFAULTS[$type];
        }

        return $settings['oper_santri'] ?? 0;
    }

    public function all()
    {
        return Salary::with('details')->latest()->get();
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
        return SalaryDetail::with('user', 'salary')->findOrFail($id);
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

        $presents = Present::with('user', 'user.teacher', 'schedule', 'schedule.batch', 'schedule.batch.course')
            ->whereHas('schedule', function ($query) use ($salary) {
                return $query
                    ->whereDate('scheduled_at', '>=', $salary->start_date)
                    ->whereDate('scheduled_at', '<=', $salary->end_date);
            })->where('type', 'teacher')
            ->get();

        $teacherPresents = $presents->groupBy(function ($present, $key) {
            return $present->user_id;
        });

        foreach ($teacherPresents as $user_id => $presents) {
            $amount = 0;
            $summary = [
                'tahsin_anak' => ['total' => 0, 'amount' => 0],
                'tahsin_dewasa' => ['total' => 0, 'amount' => 0],
                'tahsin_balita' => ['total' => 0, 'amount' => 0],
                'talaqqi_jamai' => ['total' => 0, 'amount' => 0],
                'own' => 0,
                'switch' => 0,
                'present' => 0,
                'late' => 0,
                'late_with_confirm' => 0,
                'late_without_confirm' => 0,
                'absent' => 0,
                'permit' => 0,
                'sick' => 0,
                'base' => 0,
                'oper_santri' => 0,
                'nominal_oper' => 0,
                'transportasi' => 0,
                'tunjangan' => 0,
                'potongan_telat' => 0,
                'maks_waktu_telat' => setting('maks_waktu_telat'),
            ];

            foreach ($presents as $present) {
                if ($present->status == Present::STATUS_PRESENT) {
                    $type = Str::snake($present->schedule->batch->course->type);
                    if ($type == 'talaqqi_pengajar') {
                        continue;
                    }

                    $ratio = $present->user->teacher->status == 'training' ? 0.5 : 1;
                    $summary[$type]['total']++;
                    $memberCount = $present->schedule->presents()->member()->count();
                    $sizeType = (new BatchService)->getSizeType($present->schedule->batch->course->type, $memberCount);
                    $rate = $this->getRate($type, $sizeType, $settings[$type], $memberCount);
                    $rate *= $ratio;
                    $summary[$type]['amount'] += $rate;
                    $summary['base'] += $rate;
                    $amount += $rate;
                    $summary[Present::STATUS_PRESENT]++;
                    $attended_at = $present->attended_at;
                    if (! $attended_at) {
                        $attended_at = $present->created_at;
                    }
                    if ($attended_at->greaterThan($present->schedule->start_at)) {
                        $diff = $attended_at->diffInMinutes($present->schedule->start_at);
                        if ($diff > $summary['maks_waktu_telat']) {
                            $summary['late']++;
                            if ($present->description) {
                                $summary['late_with_confirm']++;
                            } else {
                                $summary['late_without_confirm']++;
                            }
                        }
                    }

                    $operCount = Present::where([
                        'schedule_id' => $present->schedule_id,
                        'type' => 'member',
                        'status' => 'present',
                        'is_transfer' => true,
                    ])
                        ->count();
                    $summary['oper_santri'] += $operCount;
                    $summary['nominal_oper'] += $operCount * $this->getOperSantriRate($type, $settings);
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

            $summary['transportasi'] = $summary['present'] * $settings['transportasi'];
            $amount += $summary['transportasi'];

            $amount += $summary['nominal_oper'];

            $late = $summary['late'] - ($summary['late_with_confirm'] > $settings['maks_telat_dengan_konfirmasi'] ? 3 : $summary['late_with_confirm']);
            $summary['potongan_telat'] = $late * $settings['telat_tanpa_konfirmasi'];
            $amount -= $summary['potongan_telat'];

            if ($summary['present'] > 0) {
                $summary['tunjangan'] = $settings['tunjangan'] * $ratio;

                $present_percentage = round($summary['present'] / $summary['own'] * 100, 0);
                $fix_amount = round($settings['tunjangan'] * 0.3);
                $present_amount = $settings['tunjangan'] - $fix_amount;
                if ($summary['absent'] > 0) {
                    $present_amount = 0;
                } elseif ($present_percentage < $settings['maks_izin']) {
                    $present_amount = 0;
                }
                if ($present_amount > 0) {
                    $present_amount = round($present_amount * $present_percentage / 100);
                } else {
                    $fix_amount = round($fix_amount * $present_percentage / 100);
                }
                $summary['tunjangan'] = $fix_amount + $present_amount;

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

    /**
     * Course types whose fee is an hourly rate (from the salary config)
     * multiplied by the class hours for its size.
     */
    public const HOURLY_COURSE_TYPES = [
        'tahsin_anak' => 'Tahsin Anak',
        'tahsin_dewasa' => 'Tahsin Dewasa',
        'tahsin_balita' => 'Tahsin Balita',
    ];

    /**
     * Paid class hours for a course type and member count, based only on class
     * size (kecil = 1 hour). Tahsin Dewasa above 15 members is capped at the
     * besar hours; its extra time is paid as a flat fee (see getRate).
     * Returns null for course types paid at a flat rate.
     */
    public function getClassHours($type, $memberCount)
    {
        if (! array_key_exists($type, self::HOURLY_COURSE_TYPES)) {
            return null;
        }

        $batchService = new BatchService;
        if ($type == 'tahsin_dewasa') {
            $memberCount = min($memberCount, BatchService::DEWASA_EXTRA_MEMBER_THRESHOLD);
        }

        $minutes = $batchService->calculateDuration(self::HOURLY_COURSE_TYPES[$type], $memberCount);

        return $minutes / 60;
    }

    public function getRate($type, $size_type, $rate, $memberCount = 0)
    {
        $hours = $this->getClassHours($type, $memberCount);
        if ($hours === null) {
            return $rate;
        }

        $fee = (float) $rate * $hours;

        if ($type == 'tahsin_dewasa') {
            $fee += (new BatchService)->getDewasaExtraMultiplier($memberCount) * BatchService::DEWASA_EXTRA_FEE;
        }

        return (int) round($fee);
    }

    public function getPresentOfSalary($salary_id, $user_id = 0)
    {
        $presents = Present::with(['user', 'schedule', 'schedule.batch', 'schedule.batch.course'])
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
            ->whereHas('salary', function ($query) {
                $query->whereNotNull('approved_at');
            })
            ->where('user_id', $teacher_id)
            ->latest()
            ->get();
    }
}
