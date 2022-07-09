<?php

namespace App\Imports;

use App\Models\Batch;
use App\Models\Course;
use App\Models\Teacher;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BatchImport implements ToModel, WithHeadingRow
{
    /**
     * @param  array  $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $teacher = Teacher::where('name', $row['teacher'])->first();
        $course = course::where('name', $row['course'])->first();

        return new Batch([
            'name' => $row['name'],
            'description' => $row['description'],
            'teacher_id' => $teacher->id,
            'course_id' => $course->id,
        ]);
    }
}
