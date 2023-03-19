<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Interfaces\MemberRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        DB::transaction(function () use ($memberRepository, $request, $id) {    
            $data = $request->validate([
                'leave_at'=>'required|date'
            ]);

            $member = $memberRepository->find($id);

            activity()
            ->on($member)
            ->by(Auth::user())
            ->event('halaqoh')
            ->log(':subject.full_name keluar dari halaqoh '.$member->batches->pluck('name')->join(',').' pada '.Carbon::parse($request->leave_at)->format('l, d M Y'));

            $member->batches()->detach();
            $member->leave_at = $data['leave_at'];
            $member->save();
        });

        return to_route('members.index')->with('message', __('Left Successfully'));
    }
}
