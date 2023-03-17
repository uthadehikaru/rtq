<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Interfaces\MemberRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        DB::transaction(function() use ($memberRepository, $id){
            $member = $memberRepository->find($id);
            $member->batches()->detach();
            $member->leave_at = Carbon::now();
            $member->save();
        });

        return to_route('members.index')->with('message', __('Left Successfully'));
    }
}
