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
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Artisan;

class MemberController extends Controller
{
    public function index(Request $request, MembersDataTable $dataTable)
    {
        if ($request->get('action') == 'export') {
            return (new MemberExport())->download('Data Anggota per '.date('d M Y H.i').'.xlsx');
        }

        $total = Member::whereHas('batches')->count();
        $data['title'] = $total.' '.__('Members');
        
        // <a href="'.route('members.cards').'" class="btn btn-warning btn-icon-sm" target="_blank">
        //     <i class="la la-image"></i>
        //     Kartu Anggota
        // </a>
        $data['buttons'] = '
        <a href="'.route('members.index', ['action' => 'export']).'" class="btn btn-success btn-icon-sm">
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

        try {
            $memberRepository->create($data);
        } catch(Exception $ex) {
            return back()->with('error', $ex->getMessage())->withInput();
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

        try {
            $memberRepository->update($member_id, $data);
        } catch(Exception $ex) {
            return back()->with('error', $ex->getMessage())->withInput();
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

    public function cards(Request $request, $member_no=null)
    {
        if($member_no){
            $file = Storage::disk('public')->get('idcards/'.$member_no.'.jpg');
            $reset = $request->has('reset');
            if($file && !$reset)
                return '<img src="'.asset('storage/idcards/'.$member_no.'.jpg').'" />';
            else{
                Artisan::call('member:card', ['--no'=>$member_no]);
                return '<img src="'.asset('storage/idcards/'.$member_no.'.jpg').'" />';
            }

        }
        $files = Storage::disk('public')->files('idcards');
        $list = "";
        foreach($files as $file){
            $list .= '<p><img src="'.asset('storage/'.$file).'" /></p>';
        }
        return Pdf::loadHTML($list)->setPaper('a4', 'landscape')->stream();
    }
}
