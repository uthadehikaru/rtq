<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\MemberRepository;
use Illuminate\Http\Request;

class GetMembers extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, MemberRepository $memberRepository)
    {
        $keyword = $request->get('q');
        if (! $keyword) {
            $keyword = 'xx';
        }
        $data['items'] = $memberRepository->search($keyword);
        $data['total_count'] = count($data['items']);

        return response()->json($data);
    }
}
