<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Services\SalaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalaryReport extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, $detail_id)
    {
        $title = 'Laporan';
        $detail = (new SalaryService())->findDetail($detail_id);
        $salary = $detail->salary;
        $teacherPresents = (new SalaryService())->getPresentOfSalary($salary->id, Auth::id());

        return view('reports.salary-detail', compact('title', 'salary', 'teacherPresents'));
    }
}
