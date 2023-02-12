<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Models\Salary;
use App\Services\SalaryService;
use Illuminate\Http\Request;

class ReportSalary extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, $salary_id, $user_id = 0)
    {
        $title = 'Laporan';
        $salary = Salary::find($salary_id);
        $teacherPresents = (new SalaryService())->getPresentOfSalary($salary_id, $user_id);

        return view('reports.salary-detail', compact('title', 'salary', 'teacherPresents', 'user_id'));
    }
}
