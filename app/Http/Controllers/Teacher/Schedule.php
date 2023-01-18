<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Repositories\BatchRepository;

class Schedule extends Controller
{
    public function __invoke(BatchRepository $batchRepository)
    {
        $data['title'] = 'Absensi';
        $data['batches'] = $batchRepository->all();
        return view('schedule-form',$data);
    }
}