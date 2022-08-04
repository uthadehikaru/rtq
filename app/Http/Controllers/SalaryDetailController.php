<?php

namespace App\Http\Controllers;

use App\Services\SalaryService;
use Illuminate\Http\Request;

class SalaryDetailController extends Controller
{
    public function index($salary_id)
    {
        $data['title'] = __('Details');
        $salary = (new SalaryService())->findDetails($salary_id);
        $details = $salary->details;
        $data['salary'] = $salary;
        $data['details'] = $details;
        $data['total'] = $details->count();

        return view('datatables.salary-details', $data);
    }

    public function create($salary_id) 
    {
        $data['title'] = __('New Salary');
        $data['salary'] = null;

        return view('forms.salary', $data);
    }

    public function store(Request $request, $salary_id) 
    {
        (new SalaryService())->store($request->all());

        return redirect()->route('salaries.index')->with('message','Created');
    }

    public function edit($salary_id, $id)
    {
        $detail = (new SalaryService())->findDetail($id);
        $data['title'] = __('Edit Salary '.$detail->teacher->name);
        $data['detail'] = $detail;

        return view('forms.salary-detail', $data);
    }

    public function update(Request $request, $salary_id, $id) 
    {
        $detail = (new SalaryService())->findDetail($id);
        $detail->amount = $request->amount;
        $detail->save();

        return redirect()->route('salaries.details.index', $detail->salary_id)->with('message','Updated');
    }

    public function destroy($salary_id, $id) 
    {
        (new SalaryService())->deleteDetail($id);

        $data['statusCode'] = 200;

        return response()->json($data);
    }
}
