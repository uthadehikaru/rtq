<?php

namespace App\Http\Controllers;

use App\DataTables\RegistrationsDataTable;
use App\Models\Registration;
use App\Repositories\BatchRepository;
use App\Repositories\RegistrationRepository;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(RegistrationsDataTable $dataTable)
    {
        return $dataTable->render('datatables.registration');
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
    public function show(BatchRepository $batchRepository, $id)
    {
        $registration = Registration::find($id);
        $data['registration'] = $registration;
        $data['batches'] = $batchRepository->getByCourseType($registration->type);

        return view('forms.registration-show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RegistrationRepository $registrationRepository, Request $request, $id)
    {
        $data = $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'registration_date' => 'required|date',
        ]);

        $registrationRepository->activate($id, $data);

        return to_route('registrations.index')->with('message', 'Aktifasi berhasil');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Registration::find($id)->delete();
        $data['statusCode'] = 200;

        return response()->json($data);
    }
}
