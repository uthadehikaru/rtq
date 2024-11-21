<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Repositories\MemberRepository;
use Illuminate\Http\Request;

class ChangeBatch extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(
        Request $request,
        MemberRepository $memberRepository, $id)
    {
        $request->validate([
            'batch_id' => 'required',
        ]);

        $memberRepository->changeBatch($id, $request->batch_id);

        return to_route('members.index')->with('message', 'Berhasil pindah halaqoh');
    }
}
