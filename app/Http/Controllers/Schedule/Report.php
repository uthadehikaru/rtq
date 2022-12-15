<?php

namespace App\Http\Controllers\Schedule;

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
    public function __invoke(PresentRepository $presentRepository,
        BatchRepository $batchRepository)
    {
        $data['presents'] = $presentRepository->all();
        $data['batches'] = $batchRepository->all();
        $data['statuses'] = Present::STATUSES;
        return view('datatables.schedule-report', $data);
    }
}
