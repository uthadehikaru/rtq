<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Interfaces\MemberRepositoryInterface;
use Illuminate\Http\Request;

class LeaveBatch extends Controller
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
        $batch = $member->batch();
        if ($batch) {
            $member->batches()->detach($batch->id);

            return to_route('members.index')->with('message', __('Left Successfully'));
        }

        return back();
    }
}
