<?php

namespace App\Repositories;

use App\Interfaces\PresentRepositoryInterface;
use App\Models\Present;

class PresentRepository implements PresentRepositoryInterface
{
    public function all()
    {
        return Present::with('schedule', 'user', 'schedule.batch')->latest()->get();
    }

    public function getBySchedule($schedule_id)
    {
        return Present::with('schedule', 'user')
        ->where('schedule_id', $schedule_id)
        ->latest()
        ->get();
    }

    public function getByTeacher($user_id)
    {
        return Present::with('schedule', 'user', 'schedule.batch')
        ->where('user_id', $user_id)
        ->latest()
        ->get();
    }

    public function count($schedule_id)
    {
        return Present::where('schedule_id', $schedule_id)->count();
    }

    public function getLatest($limit = 10)
    {
        return Present::latest()->paginate($limit);
    }

    public function find($id)
    {
        return Present::findOrFail($id);
    }

    public function delete($id)
    {
        Present::destroy($id);
    }

    public function create(array $data)
    {
        return Present::create($data);
    }

    public function update($id, array $data)
    {
        return Present::whereId($id)->update($data);
    }
}
