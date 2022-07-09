<?php

namespace App\Repositories;

use App\Interfaces\MemberRepositoryInterface;
use App\Models\Member;

class MemberRepository implements MemberRepositoryInterface
{
    public function all()
    {
        return Member::with('batches', 'batches.course')->orderBy('full_name')->get();
    }

    public function count()
    {
        return Member::count();
    }

    public function getLatest($limit = 10)
    {
        return Member::latest()->paginate($limit);
    }

    public function find($id)
    {
        return Member::findOrFail($id);
    }

    public function delete($id)
    {
        Member::destroy($id);
    }

    public function create(array $data)
    {
        return Member::create($data);
    }

    public function update($id, array $data)
    {
        return Member::whereId($id)->update($data);
    }
}
