<?php

namespace App\Repositories;

use App\Interfaces\BatchRepositoryInterface;
use App\Models\Batch;

class BatchRepository implements BatchRepositoryInterface 
{

    public function getByCourse($course_id) 
    {
        return Batch::where('course_id', $course_id)->get();
    }

    public function countByCourse($course_id) 
    {
        return Batch::where('course_id', $course_id)->count();
    }
    
    public function getLatest($limit=10) 
    {
        return Batch::latest()->paginate($limit);
    }

    public function find($id) 
    {
        return Batch::findOrFail($id);
    }

    public function delete($id) 
    {
        Batch::destroy($id);
    }

    public function create(array $data) 
    {
        return Batch::create($data);
    }

    public function update($id, array $data) 
    {
        return Batch::whereId($id)->update($data);
    }
}