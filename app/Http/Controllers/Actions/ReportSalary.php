<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Models\Salary;
use App\Models\SalaryDetail;
use App\Services\SalaryService;
use Illuminate\Http\Request;

class ReportSalary extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, $salary_id, $detail_id = 0)
    {
        $salary = Salary::find($salary_id);
        $detail = (new SalaryService())->findDetail($detail_id);
        $title = 'Bisyaroh '.$salary->name. ' - '.$detail->user->name;
        $teacherPresents = (new SalaryService())->getPresentOfSalary($salary_id, $detail->user_id);

        return view('reports.salary-detail', compact('title', 'salary', 'teacherPresents', 'detail'));
    }
}
