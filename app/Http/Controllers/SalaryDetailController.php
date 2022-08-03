<?php

namespace App\Http\Controllers;

use App\Services\SalaryService;
use Illuminate\Http\Request;

class SalaryDetailController extends Controller
{
    public function index($id)
    {
        $data['title'] = __('Details');
        $salary = (new SalaryService())->findDetails($id);
        $details = $salary->details;
        $data['salary'] = $salary;
        $data['details'] = $details;
        $data['total'] = $details->count();

        return view('datatables.salary-details', $data);
    }

    public function create() 
    {
        $data['title'] = __('New Salary');
        $data['salary'] = null;

        return view('forms.salary', $data);
    }

    public function store(Request $request) 
    {
        (new SalaryService())->store($request->all());

        return redirect()->route('salaries.index')->with('message','Created');
    }

    public function edit($id)
    {
        $data['title'] = __('Edit Salary');
        $data['salary'] = (new SalaryService())->find($id);

        return view('forms.salary', $data);
    }

    public function update(Request $request, $id) 
    {
        (new SalaryService())->update($id, $request->all());

        return redirect()->route('salaries.index')->with('message','Updated');
    }

    public function destroy($id) 
    {
        (new SalaryService())->delete($id);

        $data['statusCode'] = 200;

        return response()->json($data);
    }
}
