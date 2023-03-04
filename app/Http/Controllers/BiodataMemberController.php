<?php

namespace App\Http\Controllers;

use App\DataTables\MembersBiodataDataTable;
use App\Http\Requests\BiodataMemberRequest;
use App\Models\Member;
use App\Models\Setting;
use App\Repositories\MemberRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class BiodataMemberController extends Controller
{
    public function index(MembersBiodataDataTable $dataTable, Request $request)
    {
        $count = Setting::where('group', 'biodata')->count();
        $verified = Setting::where('group', 'biodata')->where('payload->verified', true)->count();
        $unverified = Setting::where('group', 'biodata')->where('payload->verified', false)->count();
        $total = Member::has('batches')->count();
        $dataTable->setStatus($request->get('status'));
        $data['title'] = $count.'/'.$total.' Biodata';
        $data['buttons'] = '
        <a href="'.url()->current().'?status=verified" class="btn btn-success mt-2">'.$verified.' Verified</a>
        <a href="'.url()->current().'?status=unverified" class="btn btn-warning mt-2">'.$unverified.' Not Verified</a>
        ';

        return $dataTable->render('datatables.datatable', $data);
    }

    public function add()
    {
        return view('member-info');
    }

    public function store(BiodataMemberRequest $request, MemberRepository $memberRepository)
    {
        $data = $request->validated();
        $data['verified'] = false;

        $error = $memberRepository->updateBiodata($data);

        if ($error) {
            return back()->with('error', $error)->withInput();
        }

        return back()->with('message', 'Terima kasih, data anda akan kami verifikasi');
    }

    public function edit($id)
    {
        DB::transaction(function () use ($id) {
            $setting = Setting::find($id);
            $member = Member::find($setting->name);
            $payload = $setting->payload;
            $member->update($payload);
            $payload['verified'] = true;
            $setting->update(['payload' => $payload]);
            Artisan::call('member:generateno');
        });

        return back()->with('message', 'Berhasil dikonfirmasi');
    }

    public function destroy($id)
    {
        Setting::find($id)->delete();
        $data['statusCode'] = 200;

        return response()->json($data);
    }
}
