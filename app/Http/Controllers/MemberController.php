<?php

namespace App\Http\Controllers;

use App\Interfaces\BatchRepositoryInterface;
use App\Interfaces\MemberRepositoryInterface;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(MemberRepositoryInterface $memberRepository)
    {
        $data['title'] = __('Members');
        $data['members'] = Member::whereHas('batches')->orderBy('full_name')->get();
        $data['total'] = $data['members']->count();

        return view('datatables.member', $data);
    }

    public function create()
    {
        $data['title'] = __('New Member');
        $data['member'] = null;

        return view('forms.member', $data);
    }

    public function store(MemberRepositoryInterface $memberRepository, Request $request)
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
        ]);

        $memberRepository->create($data);

        return redirect()->route('members.index')->with('message', __('Created Successfully'));
    }

    public function edit(MemberRepositoryInterface $memberRepository, $member_id)
    {
        $data['title'] = __('Edit Member');
        $data['member'] = $memberRepository->find($member_id);

        return view('forms.member', $data);
    }

    public function update(MemberRepositoryInterface $memberRepository, Request $request, $member_id)
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
        ]);

        $memberRepository->update($member_id, $data);

        return redirect()->route('members.index')->with('message', __('Updated Successfully'));
    }

    public function destroy(MemberRepositoryInterface $memberRepository, $member_id)
    {
        $status = $memberRepository->delete($member_id);
        $data['statusCode'] = 200;

        return response()->json($data);
    }

    public function json(MemberRepositoryInterface $memberRepository)
    {
        $data['items'] = $memberRepository->all();
        $data['total_count'] = 10;

        return response()->json($data);
    }

    public function change(MemberRepositoryInterface $memberRepository,
        BatchRepositoryInterface $batchRepository,
        $member_id)
    {
        $data['title'] = __('Change :name', ['name' => __('Batch')]);
        $data['member'] = $memberRepository->find($member_id);
        $data['batches'] = $batchRepository->all();

        return view('forms.member-change', $data);
    }

    public function switch(MemberRepositoryInterface $memberRepository,
        BatchRepositoryInterface $batchRepository,
        $member_id)
    {
        $member = $memberRepository->find($member_id);
        $data['title'] = __('Switch Member').' '.$member->full_name.' - '.$member->batchName();
        $data['member'] = $member;
        $data['members'] = $memberRepository->all();

        return view('forms.member-switch', $data);
    }
}
