<?php

namespace App\Http\Controllers;
use App\Interfaces\CourseRepositoryInterface;

use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(CourseRepositoryInterface $courseRepository)
    {
        $data['title'] = __('Courses');
        $data['courses'] = $courseRepository->all();
        $data['total'] = $courseRepository->count();
        return view('course', $data);
    }

    public function json(CourseRepositoryInterface $courseRepository)
    {
        $courses = $courseRepository->all();
        return response()->json($courses);
    }
}
