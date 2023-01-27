<?php

namespace App\Http\Controllers\Schedule;

use App\DataTables\PresentsDataTable;
use App\Http\Controllers\Controller;
use App\Models\Present;
use App\Repositories\BatchRepository;
use App\Repositories\PresentRepository;

class Report extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(PresentsDataTable $dataTable, BatchRepository $batchRepository)
    {
        $data['title'] = 'Laporan';
        $data['batches'] = $batchRepository->all();
        $data['statuses'] = Present::STATUSES;
        $data['buttons'] = '
            <a href="'.route('schedules.export').'" class="btn btn-primary btn-icon-sm">
                <i class="la la-download"></i>
                Export (.xls)
            </a>
        ';

        return $dataTable->render('datatables.datatable',$data);
    }
}
