<?php

namespace App\Http\Controllers\Actions;

use App\Exports\CourseExport;
use App\Http\Controllers\Controller;
use App\Models\Course;

class ExportBatch extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke($course_id)
    {
        $course = Course::find($course_id);
        return (new CourseExport($course_id))->download('Data Kelas '.$course->name.'.xlsx');
    }
}
