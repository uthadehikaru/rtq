<?php

namespace App\Http\Controllers;

use App\Interfaces\BatchRepositoryInterface;
use App\Interfaces\ScheduleRepositoryInterface;
use App\Interfaces\TeacherRepositoryInterface;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Auth;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ScheduleRepositoryInterface $scheduleRepository)
    {
        $data['title'] = __('Schedules');
        if(Auth::user()->hasRole('administrator'))
            $data['schedules'] = $scheduleRepository->all();
        else
            $data['schedules'] = $scheduleRepository->getByTeacher(Auth::user()->teacher->id);

        return view('datatables.schedule', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(
        BatchRepositoryInterface $batchRepository,
        TeacherRepositoryInterface $teacherRepository,
    ) {
        $data['title'] = __('New Schedule');
        $data['schedule'] = null;
        $data['batches'] = $batchRepository->all();
        $data['teachers'] = $teacherRepository->all();

        return view('forms.schedule', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        Request $request,
        ScheduleRepositoryInterface $scheduleRepository
    ) {
        $request->validate([
            'scheduled_at' => 'required',
            'batch_id' => 'required',
        ]);

        $schedule = $request->all();
        $scheduleRepository->create($schedule);

        return redirect()->route('schedules.index')->with('message', __('Created Successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(
        BatchRepositoryInterface $batchRepository,
        TeacherRepositoryInterface $teacherRepository,
        Schedule $schedule
    ) {
        $data['title'] = __('Edit Schedule');
        $data['schedule'] = $schedule;
        $data['batches'] = $batchRepository->all();
        $data['teachers'] = $teacherRepository->all();

        return view('forms.schedule', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,
        ScheduleRepositoryInterface $scheduleRepository,
        $id,
    ) {
        $schedule = $request->validate([
            'scheduled_at' => 'required',
            'batch_id' => 'required',
            'teacher_id' => '',
        ]);
        $scheduleRepository->update($id, $schedule);

        return redirect()->route('schedules.index')->with('message', __('Updated Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(
        ScheduleRepositoryInterface $scheduleRepository,
        $id
    ) {
        $status = $scheduleRepository->delete($id);
        $data['statusCode'] = 200;

        return response()->json($data);
    }
}
