<?php

namespace App\Http\Controllers;

use App\Interfaces\TeacherRepositoryInterface;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TeacherRepositoryInterface $teacherRepository)
    {
        $data['title'] = __('Teachers');
        $data['teachers'] = $teacherRepository->all();
        $data['total'] = $teacherRepository->count();

        return view('datatables.teacher', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['title'] = __('New Teacher');
        $data['teacher'] = null;

        return view('forms.teacher', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        Request $request,
        TeacherRepositoryInterface $teacherRepository
    ) {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);

        $teacherRepository->create($data);

        return redirect()->route('teachers.index')->with('message', __('Created Successfully'));
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
        TeacherRepositoryInterface $teacherRepository,
        $id
    ) {
        $data['title'] = __('Edit Teacher');
        $data['teacher'] = $teacherRepository->find($id);

        return view('forms.teacher', $data);
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
        TeacherRepositoryInterface $teacherRepository,
        $id)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);

        $teacherRepository->update($id, $data);

        return redirect()->route('teachers.index')->with('message', __('Updated Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TeacherRepositoryInterface $teacherRepository, $id)
    {
        $status = $teacherRepository->delete($id);
        $data['statusCode'] = 200;

        return response()->json($data);
    }
}
