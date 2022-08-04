<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Services\SalaryService;
use Illuminate\Http\Request;
use Auth;

class Salary extends Controller
{
    public function index(Request $request)
    {
        $data['title'] = __('Salaries');
        $data['details'] = (new SalaryService())->getTeacherSalaries(Auth::user()->teacher->id);
        return view('datatables.teacher-salary', $data);
    }

    public function report(Request $request, $detail_id)
    {
        $title ="Laporan";
        $detail = (new SalaryService())->findDetail($detail_id);
        $details[] = $detail;
        $salary = $detail->salary;
        $teacherPresents = (new SalaryService())->getPresentOfSalary($detail->salary->id, Auth::user()->teacher->id);
        return view('reports.salary-detail', compact('title','salary','details','teacherPresents'));
    }
}
