<?php

namespace App\Imports;

use App\Models\Batch;
use App\Models\Course;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BatchImport implements ToModel, WithHeadingRow
{
    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $course = course::where('name', $row['course'])->first();

        return Batch::firstOrCreate([
            'name' => $row['name'],
            'course_id' => $course->id,
        ], [
            'description' => $row['description'],
        ]);
    }
}
