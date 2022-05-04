<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\MemberRepositoryInterface;

class MemberController extends Controller
{
    public function index(MemberRepositoryInterface $memberRepository)
    {
        $data['title'] = __('Members');
        $data['members'] = $memberRepository->all();
        $data['total'] = $memberRepository->count();
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
            'full_name'=>'required',
            'short_name'=>'',
            'email'=>'required|email',
            'gender'=>'required',
            'phone'=>'',
            'address'=>'',
            'postcode'=>'',
        ]);

        $memberRepository->create($data);
        return redirect()->route('members.index')->with('message',__('Created Successfully'));
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
            'full_name'=>'required',
            'short_name'=>'',
            'email'=>'required|email',
            'gender'=>'required',
            'phone'=>'',
            'address'=>'',
            'postcode'=>'',
        ]);

        $memberRepository->update($member_id, $data);
        return redirect()->route('members.index')->with('message',__('Updated Successfully'));
    }

    public function destroy(MemberRepositoryInterface $memberRepository, $member_id)
    {
        $status = $memberRepository->delete($member_id);
        $data['statusCode'] = 200;
        return response()->json($data);
    }
}
