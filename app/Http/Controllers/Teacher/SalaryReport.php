<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Services\SalaryService;
use Auth;
use Illuminate\Http\Request;

class SalaryReport extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, $detail_id)
    {
        $title = 'Laporan';
        $detail = (new SalaryService())->findDetail($detail_id);
        $salary = $detail->salary;
        $teacherPresents = (new SalaryService())->getPresentOfSalary($salary_id, Auth::user()->teacher->id);

        return view('reports.salary-detail', compact('title', 'salary', 'teacherPresents'));
    }
}
