<?php

namespace App\Imports;

use App\Models\Batch;
use App\Models\Teacher;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ScheduleImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $batch = Batch::where('name',$row['batch'])->firstOrFail();
            $teacher = Teacher::where('name',$row['teacher'])->firstOrFail();

            $batch->teachers()->attach($teacher->id, ['is_backup'=>$row['is_backup']]);
        }
    }
}
