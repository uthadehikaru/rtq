<?php

namespace App\Repositories;

use App\Interfaces\TeacherRepositoryInterface;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TeacherRepository implements TeacherRepositoryInterface
{
    public function all()
    {
        return Teacher::withCount('batches')->latest()->get();
    }

    public function count()
    {
        return Teacher::count();
    }

    public function getLatest($limit = 10)
    {
        return Teacher::latest()->paginate($limit);
    }

    public function find($id)
    {
        return Teacher::findOrFail($id);
    }

    public function delete($id)
    {
        $teacher = Teacher::find($id);
        $teacher->delete($id);
        $teacher->user()->delete();
    }

    public function create(array $data)
    {
        DB::beginTransaction();
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make('bismillah'),
            'type' => 'teacher',
        ]);

        $user->assignRole('teacher');

        $teacher = Teacher::create([
            'name' => $data['name'],
            'status' => $data['status'],
            'user_id' => $user->id,
        ]);

        if (isset($data['batch_ids'])) {
            $teacher->batches()->sync($data['batch_ids']);
        }
        DB::commit();

        return $teacher;
    }

    public function update($id, array $data)
    {
        DB::beginTransaction();
        $teacher = Teacher::find($id);
        $teacher->update([
            'name' => $data['name'],
            'status' => $data['status'],
        ]);
        $teacher->user()->update(['email' => $data['email'], 'name' => $data['name']]);
        if (isset($data['batch_ids'])) {
            $teacher->batches()->sync($data['batch_ids']);
        }
        DB::commit();

        return $teacher;
    }

    public function list()
    {
        return Teacher::whereNotIn('status',['khidmat'])->pluck('name', 'id');
    }

    public function listAll()
    {
        return Teacher::all()->pluck('name', 'id');
    }
}
