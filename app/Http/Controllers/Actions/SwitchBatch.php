<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Interfaces\MemberRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SwitchBatch extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(
        Request $request,
        MemberRepositoryInterface $memberRepository, $id)
    {
        $member = $memberRepository->find($id);
        $otherMember = $memberRepository->find($request->member_id);
        $otherBatch = $otherMember->batch();
        $memberBatch = $member->batch();
        
        DB::beginTransaction();
        $member->batches()->detach($memberBatch);
        $member->batches()->attach($otherBatch);
        $otherMember->batches()->detach($otherBatch);
        $otherMember->batches()->attach($memberBatch);
        DB::commit();

        return to_route('members.index')->with('message', __('Switched Successfully'));
    }
}
