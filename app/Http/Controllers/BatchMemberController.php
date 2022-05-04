<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\BatchRepositoryInterface;

class BatchMemberController extends Controller
{
    public function index(BatchRepositoryInterface $batchRepository, $course_id, $batch_id)
    {
        $batch = $batchRepository->find($batch_id);
        $data['title'] = __('Batch Members');
        $data['batch'] = $batch;
        $data['total'] = $batch->members()->count();
        return view('batchmember', $data);
    }
}
