<?php

namespace App\Http\Controllers;

use App\DataTables\MembersDataTable;
use App\Exports\MemberExport;
use App\Http\Requests\MemberRequest;
use App\Models\Member;
use App\Repositories\BatchRepository;
use App\Repositories\MemberRepository;
use Exception;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request, MembersDataTable $dataTable)
    {
        if ($request->get('action') == 'export') {
            return (new MemberExport())->download('Data Anggota per '.date('d M Y H.i').'.xlsx');
        }

        $total = Member::whereHas('batches')->count();
        $data['title'] = $total.' '.__('Members');
        $data['buttons'] = '
        <a href="'.route('members.index', ['action'=>'export']).'" class="btn btn-success btn-icon-sm">
            <i class="la la-download"></i>
            Export (.xls)
        </a>
        <a href="'.route('members.create').'" class="btn btn-primary btn-icon-sm">
            <i class="la la-plus"></i>
            New Member
        </a>';
        return $dataTable->render('datatables.datatable', $data);
    }

    public function create(BatchRepository $batchRepository)
    {
        $data['title'] = __('New Member');
        $data['member'] = null;
        $data['batches'] = $batchRepository->allWithTotalMembers();

        return view('forms.member', $data);
    }

    public function store(MemberRepository $memberRepository, MemberRequest $request)
    {
        $data = $request->validated();

        try{
            $memberRepository->create($data);
        }catch(Exception $ex){
            return back()->with('error',$ex->getMessage())->withInput();
        }

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

    public function update(MemberRepository $memberRepository, MemberRequest $request, $member_id)
    {
        $data = $request->validated();

        try{
            $memberRepository->update($member_id, $data);
        }catch(Exception $ex){
            return back()->with('error',$ex->getMessage())->withInput();
        }

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
