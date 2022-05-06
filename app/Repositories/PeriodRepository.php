<?php

namespace App\Repositories;

use App\Interfaces\PeriodRepositoryInterface;
use App\Models\Period;

class PeriodRepository implements PeriodRepositoryInterface 
{

    public function all() 
    {
        return Period::all();
    }

    public function count() 
    {
        return Period::count();
    }
    
    public function getLatest($limit=10) 
    {
        return Period::latest()->paginate($limit);
    }

    public function find($id) 
    {
        return Period::findOrFail($id);
    }

    public function delete($id) 
    {
        Period::destroy($id);
    }

    public function create(array $data) 
    {
        return Period::create($data);
    }

    public function update($id, array $data) 
    {
        return Period::whereId($id)->update($data);
    }
}