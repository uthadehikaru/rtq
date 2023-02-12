<?php

namespace App\Exports;

use App\Models\Course;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CourseExport implements WithMultipleSheets
{
    use Exportable;

    private Course $course;

    public function __construct($course_id)
    {
        $this->course = Course::find($course_id);
    }

    public function sheets(): array
    {
        $batches = [];

        foreach ($this->course->batches as $batch) {
            $batches[] = new BatchExport($batch->id);
        }

        return $batches;
    }
}
