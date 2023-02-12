<?php

namespace App\Http\Controllers;

use App\Interfaces\BatchRepositoryInterface;
use App\Repositories\BatchRepository;
use Illuminate\Http\Request;

class BatchMemberController extends Controller
{
    public function index(BatchRepositoryInterface $batchRepository, $course_id, $batch_id)
    {
        $batch = $batchRepository->find($batch_id);
        $data['title'] = __('Members');
        $data['batch'] = $batch;
        $data['total'] = $batch->members()->count();
        $data['members'] = $batchRepository->getAvailableMembers($batch_id);

        return view('datatables.batchmember', $data);
    }

    public function create(BatchRepositoryInterface $batchRepository, Request $request, $course_id, $batch_id)
    {
        $batch = $batchRepository->find($batch_id);

        $data = $request->validate([
            'member_id' => 'required',
        ]);

        $batch->members()->attach($data['member_id']);

        return back()->with('message', 'Added Successfully');
    }

    public function destroy(BatchRepositoryInterface $batchRepository, $course_id, $batch_id, $member_id)
    {
        $status = $batchRepository->removeMember($batch_id, $member_id);
        $data['statusCode'] = 200;

        return response()->json($data);
    }

    public function json(BatchRepository $batchRepository, Request $request)
    {
        $keyword = $request->get('q');
        if (! $keyword) {
            $keyword = 'xx';
        }
        $data['items'] = $batchRepository->getBatchMembers($keyword);
        $data['total_count'] = count($data['items']);

        return response()->json($data);
    }
}
