<?php

namespace App\Http\Controllers;

use App\DataTables\SchedulesDataTable;
use App\Models\Schedule;
use App\Repositories\BatchRepository;
use App\Repositories\ScheduleRepository;
use App\Repositories\TeacherRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SchedulesDataTable $dataTable)
    {
        $data['title'] = __('Schedules');
        if (Auth::user()->hasRole('administrator')) {
            $data['buttons'] = '<a href="'.route('schedules.report').'" class="btn btn-success btn-icon-sm">
                <i class="la la-file"></i>
                Laporan
            </a>
            <a href="'.route('schedules.create').'" class="btn btn-primary btn-icon-sm">
                <i class="la la-plus"></i>
                Tambah Jadwal
            </a>';
        } else {
            $dataTable->setUserId(Auth::id());
        }

        return $dataTable->render('datatables.datatable', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(
        BatchRepository $batchRepository,
        TeacherRepository $teacherRepository,
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
        ScheduleRepository $scheduleRepository
    ) {
        $request->validate([
            'scheduled_at' => 'required|date',
            'batch_id' => 'required',
            'start_at' => 'required',
            'teacher_ids' => 'required',
            'place' => 'required',
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
        BatchRepository $batchRepository,
        TeacherRepository $teacherRepository,
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
        ScheduleRepository $scheduleRepository,
        $id,
    ) {
        $data = $request->validate([
            'scheduled_at' => 'required|date',
            'batch_id' => 'required',
            'start_at' => 'required',
            'end_at' => '',
            'place' => 'required',
        ]);
        $schedule = $scheduleRepository->find($id);
        $schedule->update($data);

        return redirect()->route('schedules.index')->with('message', __('Updated Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(
        ScheduleRepository $scheduleRepository,
        $id
    ) {
        $status = $scheduleRepository->delete($id);
        $data['statusCode'] = 200;

        return response()->json($data);
    }
}
