<?php

namespace App\Repositories;

use App\Interfaces\PresentRepositoryInterface;
use App\Models\Present;

class PresentRepository implements PresentRepositoryInterface
{
    public function getBySchedule($schedule_id)
    {
        return Present::with('schedule', 'teacher', 'member')
        ->where('schedule_id', $schedule_id)
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
