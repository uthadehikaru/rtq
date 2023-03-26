<?php

namespace App\Http\Controllers\Schedule;

use App\DataTables\ReportPresentsDataTable;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class Report extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, ReportPresentsDataTable $dataTable)
    {
        $data['title'] = 'Laporan';
        $data['start_date'] = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $data['end_date'] = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $dataTable->filterDate($data['start_date'], $data['end_date']);
        if ($request->has('type')) {
            $data['title'] .= ' '.__($request->type);
            $dataTable->filterType($request->type);
        }
        $data['type'] = $request->get('type');

        return $dataTable->render('datatables.report', $data);
    }
}
