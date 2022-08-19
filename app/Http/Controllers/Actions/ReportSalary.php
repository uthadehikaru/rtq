<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Services\SalaryService;
use App\Models\Present;
use Illuminate\Http\Request;

class ReportSalary extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, $salary_id)
    {
        $title ="Laporan";
        $salary = (new SalaryService())->findDetails($salary_id);
        $teacherPresents = (new SalaryService())->getPresentOfSalary($salary_id);
        return view('reports.salary-detail', compact('title','salary','teacherPresents'));
    }
}
