<?php

namespace App\Imports;

use App\Models\Batch;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MemberImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $levels = ['i' => 'Iqro', 'q' => 'Quran'];
            $batch = Batch::where('name', $row['batch'])->first();
            if(!$batch)
                throw new \Exception('no batch for '.$row['batch']);

            if(!$row['name'])
                continue;
            
            $member = Member::firstOrCreate([
                'full_name' => $row['name'],
            ],[
                'school' => $row['school'],
                'class' => $row['class'],
                'phone' => $row['phone'],
                'gender' => $row['gender'],
                'level' => $row['level'] ? $levels[strtolower($row['level'])] : null,
                'gender'=>$row['gender'],
            ]);

            $member->batches()->attach($batch->id);
        }
    }
}
