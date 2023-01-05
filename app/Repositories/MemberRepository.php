<?php

namespace App\Repositories;

use App\Interfaces\MemberRepositoryInterface;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'email' => $data['email'],
                'name' => $data['full_name'],
                'password' => Hash::make(Str::random(8)),
            ]);

            $data['user_id'] = $user->id;
            $member = Member::create($data);

            if(isset($data['batch_id']))
                $member->batches()->sync($data['batch_id']);

            return $member;
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $member = Member::with('user')->find($id);

            if ($member->user) {
                $member->user()->update([
                    'name' => $data['full_name'],
                    'email' => $data['email'],
                ]);
            } else {
                $user = User::create([
                    'email' => $data['email'],
                    'name' => $data['full_name'],
                    'password' => Hash::make(Str::random(8)),
                ]);
                $data['user_id'] = $user->id;
            }

            $member->update($data);

            if(isset($data['batch_id']))
                $member->batches()->sync($data['batch_id']);

            return $member;
        });
    }

    public function countActiveMembers(): int
    {
        return Member::has('batches')->count();
    }
}
