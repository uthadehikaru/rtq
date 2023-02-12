<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Models\Salary;
use Illuminate\Http\Request;

class ApproveSalary extends Controller
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
        $salary->update(['approved_at' => now()]);

        return to_route('salaries.details.index', $salary_id)->with('message', __('Perhitungan telah disetujui'));
    }
}
