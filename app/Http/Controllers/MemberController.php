<?php

namespace App\Http\Controllers;

use App\DataTables\MembersDataTable;
use App\Exports\MemberExport;
use App\Http\Requests\MemberRequest;
use App\Models\Member;
use App\Repositories\BatchRepository;
use App\Repositories\MemberRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

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
        <div class="btn-group" role="group">
            <button id="action" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Aksi
            </button>
            <div class="dropdown-menu" aria-labelledby="action" style="">
                <a href="'.route('members.index', ['action' => 'export']).'" class="dropdown-item">
                    Export (.xls)
                </a>
                <a href="'.route('members.create').'" class="dropdown-item">
                    Tambah Anggota
                </a>
                <a href="'.route('members.pictures').'" class="dropdown-item" target="member-pictures">
                    Foto Anggota
                </a>
                <a href="'.route('members.cards').'" class="dropdown-item" target="member-cards">
                    Kartu Anggota
                </a>
            </div>
        </div>';

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
        } catch (Exception $ex) {
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
        } catch (Exception $ex) {
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

    public function leave(MemberRepository $memberRepository,
        $member_id)
    {
        $member = $memberRepository->find($member_id);
        $data['title'] = 'Mengeluarkan '.$member->full_name.' - '.$member->batchName();
        $data['member'] = $member;

        return view('forms.member-leave', $data);
    }

    public function cards($member_no = null)
    {
        if ($member_no) {
            $member = Member::where('member_no', $member_no)->first();
            Artisan::call('member:card', ['--no' => $member_no]);
            $member->updated_at = Carbon::now();
            $member->save();

            return '<img src="'.asset('storage/idcards/'.$member_no.'.jpg').'?v='.$member->updated_at.'" />';
        }
        $files = Storage::disk('public')->files('idcards');
        $list = '';
        foreach ($files as $file) {
            $list .= '<p><img src="'.asset('storage/'.$file).'" /></p>';
        }

        return Pdf::loadHTML($list)->setPaper('a4', 'landscape')->stream();
    }
}
