<?php

namespace App\Http\Controllers;

use App\DataTables\CoursesDataTable;
use App\Interfaces\CourseRepositoryInterface;
use App\Models\Course;
use Exception;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(CoursesDataTable $dataTable)
    {
        $data['title'] = __('Courses');

        return $dataTable->render('datatables.course', $data);
    }

    public function create(Request $request)
    {
        $data['title'] = __('New Course');
        $data['types'] = Course::TYPES;
        $data['course'] = null;

        return view('forms.course', $data);
    }

    public function store(CourseRepositoryInterface $courseRepository, Request $request)
    {
        $request->validate([
            'name' => 'required',
            'type' => 'required',
        ]);

        $course = $request->all();
        $courseRepository->create($course);

        return redirect()->route('courses.index')->with('message', __('Created Successfully'));
    }

    public function edit(CourseRepositoryInterface $courseRepository, $course_id)
    {
        $data['title'] = __('Edit Course');
        $data['types'] = Course::TYPES;
        $data['course'] = $courseRepository->find($course_id);

        return view('forms.course', $data);
    }

    public function update(CourseRepositoryInterface $courseRepository, Request $request, $course_id)
    {
        $data = $request->validate([
            'name' => 'required',
            'type' => 'required',
        ]);

        $courseRepository->update($course_id, $data);

        return redirect()->route('courses.index')->with('message', __('Updated Successfully'));
    }

    public function destroy(CourseRepositoryInterface $courseRepository, $course_id)
    {
        try {
            $courseRepository->delete($course_id);
            $data['statusCode'] = 200;
        } catch (Exception $ex) {
            $data['statusCode'] = 500;
            $data['message'] = $ex->getMessage();
        }

        return response()->json($data);
    }
}
