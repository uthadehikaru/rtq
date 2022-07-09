<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Interfaces\MemberRepositoryInterface;
use Illuminate\Http\Request;

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
        $batch_id = $member->batch()->id;
        $otherMember = $memberRepository->find($request->member_id);
        $otherBatch_id = $otherMember->batch()->id;
        $member->batches()->sync($otherBatch_id);
        $otherMember->batches()->sync($batch_id);

        return to_route('members.index')->with('message', __('Switched Successfully'));
    }
}
