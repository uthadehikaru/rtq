<?php

namespace App\Imports;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TeacherImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $email = $row['name'].'@rtqmaisuro.id';
            if ($row['email']) {
                $email = $row['email'];
            }

            $user = User::firstOrCreate([
                'name' => $row['name'],
            ], [
                'email' => $email,
                'password' => Hash::make('bismillah'),
            ]);

            $user->assignRole('teacher');

            Teacher::firstOrCreate([
                'name' => $row['name'],
                'user_id' => $user->id,
            ]);
        }
    }
}
