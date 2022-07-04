<?php

namespace App\Imports;

use App\Models\Member;
use App\Models\Batch;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Events\AfterImport;

class MemberImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            $levels = ['i'=>'Iqro','q'=>'Quran'];
            $batch = Batch::where('name',$row['batch'])->first();

            $member = Member::create([
                'full_name'=>$row['name'],
                'school'=>$row['school'],
                'class'=>$row['class'],
                'level'=>$row['level']?$levels[strtolower($row['level'])]:null,
            ]);

            $member->batches()->attach($batch->id);
        }
    }
}
