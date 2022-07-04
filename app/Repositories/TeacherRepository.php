<?php

namespace App\Repositories;

use App\Interfaces\TeacherRepositoryInterface;
use App\Models\Teacher;

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
    
    public function getLatest($limit=10) 
    {
        return Teacher::latest()->paginate($limit);
    }

    public function find($id) 
    {
        return Teacher::findOrFail($id);
    }

    public function delete($id) 
    {
        Teacher::destroy($id);
    }

    public function create(array $data) 
    {
        return Teacher::create($data);
    }

    public function update($id, array $data) 
    {
        return Teacher::whereId($id)->update($data);
    }
}