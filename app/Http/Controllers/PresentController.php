<?php

namespace App\Http\Controllers;

use App\Interfaces\PresentRepositoryInterface;
use App\Models\Present;
use App\Models\Schedule;
use Illuminate\Http\Request;

class PresentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(
        PresentRepositoryInterface $presentRepository,
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
        PresentRepositoryInterface $presentRepository,
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        PresentRepositoryInterface $presentRepository,
        Schedule $schedule,
        Present $present,
    ) {
        $data['title'] = __('Edit Present').' '.$present->name();
        $data['schedule'] = $schedule;
        $data['present'] = $present;
        $data['statuses'] = Present::STATUSES;

        return view('forms.present', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(
        Request $request,
        PresentRepositoryInterface $presentRepository,
        Schedule $schedule,
        $id
    ) {
        $data = $request->validate([
            'status' => 'required',
            'description' => '',
            'attended_at' => '',
        ]);
        if ($data['status'] != 'present') {
            $data['attended_at'] = null;
        }

        $presentRepository->update($id, $data);

        return redirect()->route('schedules.presents.index', $schedule->id)->with('message', __('Updated Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
