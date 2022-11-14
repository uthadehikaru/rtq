<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RTQImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new TeacherImport(),
            new CourseImport(),
            new BatchImport(),
            new MemberImport(),
            new ScheduleImport(),
        ];
    }
}
