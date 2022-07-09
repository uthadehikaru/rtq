<?php

namespace App\Repositories;

use App\Interfaces\TeacherRepositoryInterface;
use App\Models\Teacher;
use App\Models\User;
use Hash;

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
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make('bismillah'),
            'type' => 'teacher',
        ]);

        return Teacher::create([
            'name' => $data['name'],
            'user_id' => $user->id,
        ]);
    }

    public function update($id, array $data)
    {
        $teacher = Teacher::find($id);
        $teacher->update(['name' => $data['name']]);
        $teacher->user()->update(['email' => $data['email']]);

        return $teacher;
    }

    public function list()
    {
        return Teacher::all()->pluck('name', 'id');
    }
}
