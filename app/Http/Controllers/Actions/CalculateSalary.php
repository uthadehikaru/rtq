<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Services\SalaryService;
use Illuminate\Http\Request;

class CalculateSalary extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke($salary_id)
    {
        $service = new SalaryService();
        $service->calculate($salary_id);
        return to_route('salaries.details.index', $salary_id)->with('message', __('Calculated'));
    }
}
