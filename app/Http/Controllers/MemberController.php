<?php

namespace App\Http\Controllers;

use App\Exports\MemberExport;
use App\Models\Member;
use App\Repositories\BatchRepository;
use App\Repositories\MemberRepository;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        if ($request->get('action') == 'export') {
            return (new MemberExport())->download('Data Anggota per '.date('d M Y H.i').'.xlsx');
        }

        $data['title'] = __('Members');
        $data['members'] = Member::with(['batches', 'batches.course'])->latest()->get();
        $data['total'] = Member::whereHas('batches')->count();

        return view('datatables.member', $data);
    }

    public function create(BatchRepository $batchRepository)
    {
        $data['title'] = __('New Member');
        $data['member'] = null;
        $data['batches'] = $batchRepository->allWithTotalMembers();

        return view('forms.member', $data);
    }

    public function store(MemberRepository $memberRepository, Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required',
            'short_name' => '',
            'email' => '',
            'gender' => 'required',
            'phone' => '',
            'address' => '',
            'postcode' => '',
            'school' => '',
            'class' => '',
            'level' => '',
            'batch_id' => '',
            'registration_date' => '',
            'status' => '',
        ]);

        $memberRepository->create($data);

        return redirect()->route('members.index')->with('message', __('Created Successfully'));
    }

    public function edit(MemberRepository $memberRepository,
        BatchRepository $batchRepository,
        $member_id)
    {
        $data['title'] = __('Edit Member');
        $data['member'] = $memberRepository->find($member_id);
        $data['batches'] = $batchRepository->allWithTotalMembers();

        return view('forms.member', $data);
    }

    public function update(MemberRepository $memberRepository, Request $request, $member_id)
    {
        $data = $request->validate([
            'full_name' => 'required',
            'short_name' => '',
            'email' => '',
            'gender' => 'required',
            'phone' => '',
            'address' => '',
            'postcode' => '',
            'school' => '',
            'class' => '',
            'level' => '',
            'batch_id' => '',
            'registration_date' => '',
            'status' => '',
        ]);

        $memberRepository->update($member_id, $data);

        return redirect()->route('members.index')->with('message', __('Updated Successfully'));
    }

    public function destroy(MemberRepository $memberRepository, $member_id)
    {
        $status = $memberRepository->delete($member_id);
        $data['statusCode'] = 200;

        return response()->json($data);
    }

    public function json(MemberRepository $memberRepository)
    {
        $data['items'] = $memberRepository->all();
        $data['total_count'] = 10;

        return response()->json($data);
    }

    public function change(MemberRepository $memberRepository,
        BatchRepository $batchRepository,
        $member_id)
    {
        $data['title'] = __('Change :name', ['name' => __('Batch')]);
        $data['member'] = $memberRepository->find($member_id);
        $data['batches'] = $batchRepository->all();

        return view('forms.member-change', $data);
    }

    public function switch(MemberRepository $memberRepository,
        $member_id)
    {
        $member = $memberRepository->find($member_id);
        $data['title'] = __('Switch Member').' '.$member->full_name.' - '.$member->batchName();
        $data['member'] = $member;
        $data['members'] = $memberRepository->all();

        return view('forms.member-switch', $data);
    }
}
