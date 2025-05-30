<?php

namespace App\Http\Controllers;

use App\Models\Present;
use App\Models\Schedule;
use App\Models\User;
use App\Repositories\PresentRepository;
use Illuminate\Http\Request;

class PresentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(
        PresentRepository $presentRepository,
        Schedule $schedule,
    ) {
        $data['title'] = __('Presents');
        $data['schedule'] = $schedule;
        $data['presents'] = $presentRepository->getBySchedule($schedule->id);
        $data['total'] = $presentRepository->count($schedule->id);
        $data['statuses'] = Present::STATUSES;

        return view('datatables.present', $data);
    }

    public function change(
        PresentRepository $presentRepository,
        Schedule $schedule,
        $id,
        $status,
    ) {
        $presentRepository->update($id, ['status' => $status]);

        return back()->with('message', __('Updated Successfully'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(
        Request $request,
        Schedule $schedule,
    ) {
        $type = $request->get('type', 'teacher');
        $data['title'] = __('Tambah Absensi');
        $data['schedule'] = $schedule;
        $data['present'] = null;
        $data['type'] = $type;
        if ($type == 'member') {
            $data['users'] = User::has('member')->orderBy('name')->pluck('name', 'id');
        } else {
            $data['users'] = User::role('teacher')->orderBy('name')->pluck('name', 'id');
        }
        $data['statuses'] = Present::STATUSES;

        return view('forms.present', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(
        Request $request,
        PresentRepository $presentRepository,
        Schedule $schedule,
    ) {
        $data = $request->validate([
            'user_id' => 'required',
            'status' => 'required',
            'description' => '',
            'attended_at' => '',
            'is_badal' => '',
            'is_transfer' => '',
            'type' => 'required|in:teacher,member',
        ]);
        if ($data['status'] != 'present') {
            $data['attended_at'] = null;
        }
        $data['schedule_id'] = $schedule->id;

        $present = Present::where([
            'schedule_id' => $schedule->id,
            'user_id' => $data['user_id'],
        ])->first();
        if ($present) {
            return back()->with('error', __('Data '.$data['type'].' sudah pernah diinput'));
        }

        $presentRepository->create($data);

        return redirect()->route('schedules.presents.index', $schedule->id)->with('message', __('Inserted Successfully'));
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
        Schedule $schedule,
        Present $present,
    ) {
        $data['title'] = __('Edit Present').' '.$present->name();
        $data['schedule'] = $schedule;
        $data['present'] = $present;
        $data['type'] = $present->type;
        $data['statuses'] = Present::STATUSES;

        return view('forms.present', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(
        Request $request,
        PresentRepository $presentRepository,
        Schedule $schedule,
        $id
    ) {
        $data = $request->validate([
            'status' => 'required',
            'description' => '',
            'attended_at' => '',
            'is_badal' => '',
            'is_transfer' => '',
        ]);
        if ($data['status'] != 'present') {
            $data['attended_at'] = null;
        }

        $presentRepository->update($id, $data);

        if ($request->get('redirect')) {
            return redirect($request->get('redirect'))->with('message', __('Updated Successfully'));
        }

        return redirect()->route('schedules.presents.index', $schedule->id)->with('message', __('Updated Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($schedule_id, $id)
    {
        Present::find($id)->delete();
        $data['statusCode'] = 200;

        return response()->json($data);
    }
}
