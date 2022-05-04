<?php

namespace App\Http\Controllers;
use App\Interfaces\BatchRepositoryInterface;

use Illuminate\Http\Request;

class BatchController extends Controller
{
    public function index(BatchRepositoryInterface $batchRepository, $course_id)
    {
        $data['title'] = __('Batches');
        $data['batches'] = $batchRepository->getByCourse($course_id);
        $data['total'] = $batchRepository->countByCourse($course_id);
        return view('batch', $data);
    }
}
