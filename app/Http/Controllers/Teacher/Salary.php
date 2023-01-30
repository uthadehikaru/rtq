<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Services\SalaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Salary extends Controller
{
    public function index(Request $request)
    {
        $data['title'] = __('Salaries');
        $data['details'] = (new SalaryService())->getTeacherSalaries(Auth::id());

        return view('datatables.teacher-salary', $data);
    }

    public function report(Request $request, $detail_id)
    {
        $title = 'Laporan';
        $detail = (new SalaryService())->findDetail($detail_id);
        if($detail->user_id!=Auth::id())
            return abort(404);
        $details[] = $detail;
        $salary = $detail->salary;
        $teacherPresents = (new SalaryService())->getPresentOfSalary($detail->salary->id, Auth::id());

        return view('reports.salary-detail', compact('title', 'salary', 'details', 'teacherPresents'));
    }
}
