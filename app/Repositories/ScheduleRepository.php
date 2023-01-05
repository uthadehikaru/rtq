<?php

namespace App\Repositories;

use App\Interfaces\ScheduleRepositoryInterface;
use App\Models\Batch;
use App\Models\Present;
use App\Models\Schedule;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
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

        $schedule = Schedule::with('batch')->whereDate('scheduled_at', Carbon::now()->startOfDay())
        ->where('batch_id', $data['batch_id'])
        ->first();

        if (! $schedule) {
            $schedule = Schedule::create($data);

            foreach ($schedule->batch->members as $member) {
                Present::create([
                    'schedule_id' => $schedule->id,
                    'user_id' => $member->user_id,
                    'status' => 'absent',
                    'type' => 'member',
                ]);
            }
        }

        foreach ($data['teacher_ids'] as $teacher_id) {
            Present::create([
                'schedule_id' => $schedule->id,
                'user_id' => $teacher_id,
                'status' => 'present',
                'type' => 'teacher',
            ]);
        }

        DB::commit();

        return $schedule;
    }

    public function createByTeacher(array $data)
    {
        DB::beginTransaction();

        $batch = Batch::find($data['batch_id']);

        $schedule = Schedule::with('batch')->whereDate('scheduled_at', Carbon::now()->startOfDay())
        ->where('batch_id', $data['batch_id'])
        ->first();

        if (! $schedule) {
            $data['scheduled_at'] = CarbonImmutable::now()->setTime($batch->start_time->hour,$batch->start_time->minute,0);
            $data['start_at'] = $batch->start_time;
            $data['place'] = $batch->place;
            $schedule = Schedule::create($data);

            foreach ($schedule->batch->members as $member) {
                Present::create([
                    'schedule_id' => $schedule->id,
                    'user_id' => $member->user_id,
                    'status' => 'absent',
                    'type' => 'member',
                ]);
            }
        }

        Present::create([
            'schedule_id' => $schedule->id,
            'user_id' => Auth::id(),
            'status' => 'present',
            'type' => 'teacher',
            'attended_at' => CarbonImmutable::now()->format('H:i'),
            'is_badal' => $data['is_badal'],
        ]);

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
            ->withCount(['presents' => function ($query) {
                $query->where('type', 'member');
            }])
            ->whereRelation('presents', 'user_id', $user_id)
            ->latest('scheduled_at')
            ->paginate(20);
    }
}
