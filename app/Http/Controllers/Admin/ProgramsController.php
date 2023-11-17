<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ProgramsDataTable;
use App\Http\Controllers\Controller;
use App\Models\Program;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProgramsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProgramsDataTable $dataTable)
    {
        $data['title'] = 'Programs';

        return $dataTable->render('datatables.program', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['title'] = __('New Program');
        $data['program'] = null;

        return view('forms.program', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|max:255',
            'slug' => 'nullable',
            'amount' => 'required|integer|min:0',
            'qty' => 'required|integer|min:0',
            'thumbnail' => 'nullable|image',
        ]);
        $data['slug'] = Str::slug($data['title']);
        Program::create($data);
        return redirect()->route('programs.index')->with('message','Program Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $program = Program::find($id);
        if($program->published_at)
            $program->published_at = null;
        else
            $program->published_at = Carbon::now();

        $program->save();
        return back()->with('message','Program updated');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['title'] = __('Edit Program');
        $data['program'] = Program::find($id);

        return view('forms.program', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $program = Program::find($id);
        $data = $request->validate([
            'title' => 'required|max:255',
            'slug' => 'nullable',
            'amount' => 'required|integer|min:0',
            'qty' => 'required|integer|min:0',
            'thumbnail' => 'nullable|image',
        ]);
        $program->update($data);
        return redirect()->route('programs.index')->with('message','Program Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $program = Program::find($id);
            $program->delete();
            $data['statusCode'] = 200;
        } catch(Exception $ex) {
            $data['statusCode'] = 500;
            $data['message'] = $ex->getMessage();
        }

        return response()->json($data);
    }
}
