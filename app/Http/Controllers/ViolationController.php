<?php

namespace App\Http\Controllers;

use App\DataTables\ViolationsDataTable;
use App\Models\User;
use App\Models\Violation;
use App\Notifications\UserIqobCreated;
use Illuminate\Http\Request;

class ViolationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ViolationsDataTable $dataTable, Request $request)
    {
        $data['title'] = 'Pelanggaran';
        $dataTable->filter($request->only(['status','type']));

        return $dataTable->render('datatables.violation', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data['violation'] = null;
        $data['users'] = User::select('id','name')
        ->orderBy('name')
        ->get();

        return view('forms.violation', $data);
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
            'violated_date' => 'required|date',
            'user_id' => 'nullable|exists:users,id',
            'description' => 'required',
            'amount' => '',
            'paid_at' => '',
        ]);

        $user = User::find($data['user_id']);
        if($user){
            $data['type'] = $user->teacher ? 'teacher' : 'member';
        }

        $violation = Violation::create($data);

        if($user)
            $user->notify(new UserIqobCreated($violation));

        if ($request->has('redirect')) {
            return redirect($request->get('redirect'))->with('message', 'Pelanggaran atas '.$violation->user->name.' berhasil ditambahkan');
        }

        return to_route('violations.index')->with('message', 'Data pelanggaran berhasil ditambahkan');
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
    public function edit($id)
    {
        $data['violation'] = Violation::findOrFail($id);
        $data['type'] = $data['violation']->type;
        $data['users'] = User::role($data['violation']->type)->orderBy('name')->get();

        return view('forms.violation', $data);
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
        $data = $request->validate([
            'violated_date' => 'required|date',
            'user_id' => 'nullable|exists:users,id',
            'description' => 'required',
            'amount' => '',
            'paid_at' => '',
        ]);

        Violation::find($id)->update($data);

        return to_route('violations.index')->with('message', 'Data pelanggaran berhasil dipernaharui');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Violation::find($id)->delete();
        $data['statusCode'] = 200;

        return response()->json($data);
    }
}
