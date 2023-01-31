<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Models\Salary;
use App\Services\SalaryService;
use Illuminate\Http\Request;

class CancelSalary extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke($salary_id)
    {
        $salary = Salary::findOrFail($salary_id);
        $salary->update(['approved_at'=>null]);

        return to_route('salaries.details.index', $salary_id)->with('message', __('Dokumen diupdate'));
    }
}
