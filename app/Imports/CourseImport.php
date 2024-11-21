<?php

namespace App\Imports;

use App\Models\Course;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CourseImport implements ToModel, WithHeadingRow
{
    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return Course::firstOrCreate([
            'name' => $row['name'],
        ], [
            'type' => $row['type'],
            'fee' => $row['fee'],
        ]);
    }
}
