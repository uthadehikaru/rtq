<?php

namespace App\Imports;

use App\Models\Batch;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MemberImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $levels = ['i' => 'Iqro', 'q' => 'Quran'];
            $batch = Batch::where('name', $row['batch'])->first();
            if (! $batch) {
                throw new \Exception('no batch for '.$row['batch']);
            }

            if (! $row['name']) {
                continue;
            }

            $email = $row['name'].'@rtqmaisuro.id';
            $user = User::firstOrCreate([
                'name' => $row['name'],
            ], [
                'email' => $email,
                'password' => Hash::make(Str::random(8)),
            ]);

            $user->assignRole('member');

            $member = Member::firstOrCreate([
                'full_name' => $row['name'],
            ], [
                'user_id' => $user->id,
                'school' => $row['school'],
                'class' => $row['class'],
                'phone' => $row['phone'],
                'gender' => $row['gender'],
                'level' => $row['level'] ? $levels[strtolower($row['level'])] : null,
                'gender' => $row['gender'],
            ]);

            $member->batches()->attach($batch->id);
        }
    }
}
