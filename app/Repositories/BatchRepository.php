<?php

namespace App\Repositories;

use App\Interfaces\BatchRepositoryInterface;
use App\Models\Batch;
use App\Models\Member;
use App\Models\Teacher;
use DB;

class BatchRepository implements BatchRepositoryInterface
{
    public function all()
    {
        return Batch::with('teacher', 'course')->get();
    }

    public function count()
    {
        return Batch::count();
    }

    public function getByCourse($course_id)
    {
        return Batch::with('teacher')->withCount('members')->where('course_id', $course_id)->get();
    }

    public function countByCourse($course_id)
    {
        return Batch::where('course_id', $course_id)->count();
    }

    public function getAvailableMembers($batch_id)
    {
        return Member::whereNotExists(function ($query) use ($batch_id) {
            $query->select(DB::raw(1))
                  ->from('batch_member')
                  ->whereColumn('batch_member.member_id', 'members.id')
                  ->where('batch_member.batch_id', $batch_id);
        })->orderBy('full_name')->get();
    }

    public function getBatchMembers($keyword = '')
    {
        return DB::table('batch_member')
        ->select('members.full_name', 'courses.name as course', 'batches.name as batch', 'batch_member.member_id', 'batch_member.batch_id')
        ->join('batches', 'batch_member.batch_id', 'batches.id')
        ->join('courses', 'batches.course_id', 'courses.id')
        ->join('members', 'batch_member.member_id', 'members.id')
        ->where('members.full_name', 'LIKE', '%'.$keyword.'%')
        ->orderBy('members.full_name')->get();
    }

    public function members($batch_id)
    {
        return Batch::find($batch_id)->members;
    }

    public function removeMember($batch_id, $member_id)
    {
        return Batch::find($batch_id)->members()->detach($member_id);
    }

    public function getLatest($limit = 10)
    {
        return Batch::latest()->paginate($limit);
    }

    public function find($id)
    {
        return Batch::findOrFail($id);
    }

    public function delete($id)
    {
        $batch = Batch::find($id);

        return $batch->destroy($id);
    }

    public function create(array $data)
    {
        return Batch::create($data);
    }

    public function update($id, array $data)
    {
        return Batch::whereId($id)->update($data);
    }

    public function list()
    {
        return Batch::all()->pluck('name', 'id');
    }

    public function getByUser($user_id)
    {
        $teacher = Teacher::where('user_id',$user_id)->first();
        if($teacher)
            return Batch::with('teacher','course')
            ->withCount('members')
            ->where('teacher_id',$teacher->id)
            ->orderBy('name')
            ->get();
    }
}
