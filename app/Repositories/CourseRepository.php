<?php

namespace App\Repositories;

use App\Interfaces\CourseRepositoryInterface;
use App\Models\Course;
use App\Models\Member;

class CourseRepository implements CourseRepositoryInterface
{
    public function all()
    {
        return Course::withCount('batches')->get();
    }

    public function count()
    {
        return Course::count();
    }

    public function getLatest($limit = 10)
    {
        return Course::latest()->paginate($limit);
    }

    public function find($id)
    {
        return Course::findOrFail($id);
    }

    public function delete($id)
    {
        Course::destroy($id);
    }

    public function create(array $data)
    {
        return Course::create($data);
    }

    public function update($id, array $data)
    {
        return Course::whereId($id)->update($data);
    }

    public function membersPerType($types)
    {
        $data = [];
        foreach ($types as $type) {
            $count = Member::whereHas('batches', function ($query) use ($type) {
                $query->whereRelation('course', 'type', $type);
            })->count();
            $data[$type] = $count;
        }

        return $data;
    }
}
