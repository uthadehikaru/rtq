<?php

namespace App\Http\Controllers\Actions;

use App\Events\BatchChanged;
use App\Http\Controllers\Controller;
use App\Interfaces\MemberRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SwitchBatch extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(
        Request $request,
        MemberRepositoryInterface $memberRepository, $id)
    {
        $member = $memberRepository->find($id);
        $otherMember = $memberRepository->find($request->member_id);
        $otherBatch = $otherMember->batches;
        $memberBatch = $member->batches;

        DB::beginTransaction();
        $old_batch = $member->batches->pluck('name')->join(',');
        $member->batches()->sync($otherBatch);
        BatchChanged::dispatch($member, $old_batch);

        $old_batch = $otherMember->batches->pluck('name')->join(',');
        $otherMember->batches()->sync($memberBatch);
        BatchChanged::dispatch($otherMember, $old_batch);
        DB::commit();

        return to_route('members.index')->with('message', 'Berhasil bertukar halaqoh');
    }
}
