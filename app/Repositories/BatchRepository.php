<?php

namespace App\Repositories;

use App\Interfaces\BatchRepositoryInterface;
use App\Models\Batch;
use App\Models\Member;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;

class BatchRepository implements BatchRepositoryInterface
{
    public function all()
    {
        return Batch::with('teachers', 'course', 'members')
        ->orderBy('name')
        ->get();
    }

    public function allWithTotalMembers()
    {
        return Batch::with('course')
        ->withCount('members')
        ->orderBy('name')
        ->get();
    }

    public function count()
    {
        return Batch::count();
    }

    public function getByCourseType($type)
    {
        $types = [];
        if(in_array($type, ['anak','balita'])){
            $types = [
                'Tahsin Anak',
                'Tahsin Balita', 
            ];
        }else{
            $types = [
                'Tahsin Dewasa', 
            ];
        }
        return Batch::with(['course'])
        ->withCount('members')
        ->whereHas('course', function ($query) use ($types) {
            $query->whereIn('type', $types);
        })
        ->get();
    }

    public function getByCourse($course_id)
    {
        return Batch::with(['teachers' => function ($query) {
            $query->wherePivot('is_backup', false);
        }])->withCount('members')->where('course_id', $course_id)->get();
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
        $members = DB::table('batch_member')
        ->selectRaw("members.id as member_id, members.full_name, GROUP_CONCAT(DISTINCT batches.name SEPARATOR ' , ') as batch")
        ->join('batches', 'batch_member.batch_id', 'batches.id')
        ->join('courses', 'batches.course_id', 'courses.id')
        ->join('members', 'batch_member.member_id', 'members.id')
        ->where('members.full_name', 'LIKE', '%'.$keyword.'%')
        ->groupBy('members.full_name', 'members.id')
        ->orderBy('members.full_name')
        ->take(10)
        ->get();
        $data = [];
        foreach ($members as $member) {
            $data[] = [
                'id' => $member->member_id,
                'text' => $member->full_name.' - halaqoh '.$member->batch,
            ];
        }

        return $data;
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
        return DB::transaction(function () use ($data) {
            $batch = Batch::create([
                'course_id' => $data['course_id'],
                'code' => $data['code'],
                'name' => $data['name'],
                'description' => $data['description'],
                'start_time' => $data['start_time'],
                'place' => $data['place'],
            ]);

            $batch->teachers()->sync($data['teacher_ids']);

            return $batch;
        });
    }

    public function update($id, array $data)
    {
        $batch = Batch::find($id);
        $batch->update($data);
        $batch->teachers()->sync($data['teacher_ids']);

        return $batch;
    }

    public function list()
    {
        return Batch::all()->pluck('name', 'id');
    }

    public function teacherBatches($user_id)
    {
        $teacher = Teacher::with('user')->where('user_id', $user_id)->firstOrFail();

        return Batch::whereRelation('teachers', 'id', $teacher->id)
        ->orderBy('name')
        ->get();
    }

    public function getByUser($user_id)
    {
        $teacher = Teacher::with('user')->where('user_id', $user_id)->firstOrFail();

        return Batch::with('course')
        ->withCount('members')
        ->whereRelation('teachers', 'id', $teacher->id)
        ->orderBy('name')
        ->get();
    }
}
