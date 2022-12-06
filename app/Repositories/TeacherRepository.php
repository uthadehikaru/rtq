<?php

namespace App\Repositories;

use App\Interfaces\TeacherRepositoryInterface;
use App\Models\Teacher;
use App\Models\User;
use Hash;
use Illuminate\Support\Facades\DB;

class TeacherRepository implements TeacherRepositoryInterface
{
    public function all()
    {
        return Teacher::withCount('batches')->get();
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
        $teacher->user()->delete();
        $teacher->delete($id);
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

        $teacher = Teacher::create([
            'name' => $data['name'],
            'user_id' => $user->id,
        ]);

        $teacher->batches()->sync($data['batch_ids']);
        DB::commit();
        return $teacher;
    }

    public function update($id, array $data)
    {
        DB::beginTransaction();
        $teacher = Teacher::find($id);
        $teacher->update(['name' => $data['name']]);
        $teacher->user()->update(['email' => $data['email']]);
        $teacher->batches()->sync($data['batch_ids']);
        DB::commit();
        return $teacher;
    }

    public function list()
    {
        return Teacher::all()->pluck('name', 'id');
    }
}
