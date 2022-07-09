<?php

namespace App\Http\Controllers;
use App\Interfaces\CourseRepositoryInterface;
use App\Interfaces\BatchRepositoryInterface;
use App\Interfaces\TeacherRepositoryInterface;

use Illuminate\Http\Request;

class BatchController extends Controller
{
    public function index(CourseRepositoryInterface $courseRepository, BatchRepositoryInterface $batchRepository, $course_id)
    {
        $data['title'] = __('Batches');
        $data['course'] = $courseRepository->find($course_id);
        $data['batches'] = $batchRepository->getByCourse($course_id);
        $data['total'] = $batchRepository->countByCourse($course_id);
        return view('datatables.batch', $data);
    }

    public function create(
        CourseRepositoryInterface $courseRepository, 
        TeacherRepositoryInterface $teacherRepository, 
        $course_id
    )
    {
        $data['title'] = __('New Batch');
        $data['course'] = $courseRepository->find($course_id);
        $data['teachers'] = $teacherRepository->list();
        $data['batch'] = null;
        return view('forms.batch', $data);
    }

    public function store(CourseRepositoryInterface $courseRepository, BatchRepositoryInterface $batchRepository, Request $request, $course_id)
    {
        $data = $request->validate([
            'name'=>'required',
            'teacher_id'=>'required',
            'description'=>'',
        ]);

        $data['course_id'] = $course_id;

        $batchRepository->create($data);
        return redirect()->route('courses.batches.index', $course_id)->with('message',__('Created Successfully'));
    }

    public function edit(
        CourseRepositoryInterface $courseRepository, 
        BatchRepositoryInterface $batchRepository, 
        TeacherRepositoryInterface $teacherRepository, 
        Request $request, 
        $course_id, $batch_id)
    {
        $data['title'] = __('Edit Batch');
        $data['course'] = $courseRepository->find($course_id);
        $data['teachers'] = $teacherRepository->list();
        $data['batch'] = $batchRepository->find($batch_id);
        return view('forms.batch', $data);
    }

    public function update(CourseRepositoryInterface $courseRepository, BatchRepositoryInterface $batchRepository, Request $request, $course_id, $batch_id)
    {
        $data = $request->validate([
            'name'=>'required',
            'teacher_id'=>'required',
            'description'=>'',
        ]);

        $batchRepository->update($batch_id, $data);
        return redirect()->route('courses.batches.index', $course_id)->with('message',__('Updated Successfully'));
    }

    public function destroy(BatchRepositoryInterface $batchRepository, $course_id, $batch_id)
    {
        $status = $batchRepository->delete($batch_id);
        $data['statusCode'] = 200;
        return response()->json($data);

    }
}
