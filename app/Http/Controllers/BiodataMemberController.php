<?php

namespace App\Http\Controllers;

use App\DataTables\MembersBiodataDataTable;
use App\Http\Requests\BiodataMemberRequest;
use App\Models\Member;
use App\Models\Setting;
use App\Repositories\MemberRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BiodataMemberController extends Controller
{
    public function index(MembersBiodataDataTable $dataTable)
    {
        $count = Setting::where('group','biodata')->count();
        $total = Member::has('batches')->count();
        $data['title'] = $count.'/'.$total.' Biodata';
        return $dataTable->render('datatables.datatable', $data);
    }

    public function add()
    {
        return view('member-info');
    }

    public function store(BiodataMemberRequest $request, MemberRepository $memberRepository)
    {
        $data = $request->validated();
        $data = $data->safe()->merge(['validated'=>false]);

        $error = $memberRepository->updateBiodata($data);

        if($error)
            return back()->with('error',$error)->withInput();

        return back()->with('message','Terima kasih, data anda akan kami verifikasi');
    }

    public function edit($id)
    {
        DB::transaction(function() use ($id){
            $setting = Setting::find($id);
            $member = Member::find($setting->name);
            $payload = $setting->payload;
            $member->update($payload);
            $payload['verified'] = true;
            $setting->update(['payload'=>$payload]);
        });
        return back()->with('message','Berhasil dikonfirmasi');
    }

    public function destroy($id)
    {
        Setting::find($id)->delete();
        $data['statusCode'] = 200;

        return response()->json($data);
    }
}
