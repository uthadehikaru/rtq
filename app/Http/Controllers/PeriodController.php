<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\PeriodRepositoryInterface;

class PeriodController extends Controller
{
    public function index(PeriodRepositoryInterface $periodRepository)
    {
        $data['title'] = __('Periods');
        $data['periods'] = $periodRepository->all();
        $data['total'] = $periodRepository->count();
        return view('datatables.period', $data);
    }
}
