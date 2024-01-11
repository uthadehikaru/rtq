<?php

namespace App\Http\Controllers;

use App\DataTables\BatchesDataTable;
use App\Repositories\BatchRepository;
use App\Repositories\CourseRepository;
use App\Repositories\TeacherRepository;
use Exception;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    public function index(CourseRepository $courseRepository, BatchRepository $batchRepository,
    BatchesDataTable $dataTable, $course_id)
    {
        $dataTable->setCourseId($course_id);

        $total = $batchRepository->countByCourse($course_id);
        $data['title'] = $total.' Halaqoh';
        $data['course'] = $courseRepository->find($course_id);

        return $dataTable->render('datatables.batch', $data);
    }

    public function create(
        CourseRepository $courseRepository,
        TeacherRepository $teacherRepository,
        $course_id
    ) {
        $data['title'] = __('New Batch');
        $course = $courseRepository->find($course_id);
        $data['course'] = $course;
        $data['teachers'] = $teacherRepository->list();
        if($course->type=='Talaqqi Pengajar')
            $data['members'] = $teacherRepository->listAll();
        $data['batch'] = null;

        return view('forms.batch', $data);
    }

    public function store(BatchRepository $batchRepository, Request $request, $course_id)
    {
        $data = $request->validate([
            'code' => 'required',
            'name' => 'required',
            'description' => '',
            'start_time' => '',
            'place' => '',
            'teacher_ids' => 'required',
            'member_ids' => 'nullable',
        ]);

        $data['course_id'] = $course_id;

        $batchRepository->create($data);

        return redirect()->route('courses.batches.index', $course_id)->with('message', __('Created Successfully'));
    }

    public function edit(
        CourseRepository $courseRepository,
        BatchRepository $batchRepository,
        TeacherRepository $teacherRepository,
        Request $request,
        $course_id, $batch_id)
    {
        $data['title'] = __('Edit Batch');
        $course = $courseRepository->find($course_id);
        $data['course'] = $course;
        $data['teachers'] = $teacherRepository->list();
        if($course->type=='Talaqqi Pengajar')
            $data['members'] = $teacherRepository->listAll();
        $data['batch'] = $batchRepository->find($batch_id);
        //dd($data['batch']->teachers);

        return view('forms.batch', $data);
    }

    public function update(BatchRepository $batchRepository, Request $request, $course_id, $batch_id)
    {
        $data = $request->validate([
            'code' => 'required',
            'name' => 'required',
            'description' => '',
            'start_time' => '',
            'place' => '',
            'teacher_ids' => 'required',
            'member_ids' => 'nullable',
        ]);

        $batchRepository->update($batch_id, $data);

        return redirect()->route('courses.batches.index', $course_id)->with('message', __('Updated Successfully'));
    }

    public function destroy(BatchRepository $batchRepository, $course_id, $batch_id)
    {
        try {
            $batchRepository->delete($batch_id);
            $data['statusCode'] = 200;
        } catch(Exception $ex) {
            $data['statusCode'] = 500;
            $data['message'] = $ex->getMessage();
        }

        return response()->json($data);
    }
}
