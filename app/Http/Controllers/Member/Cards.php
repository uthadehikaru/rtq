<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class Cards extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $data['members'] = Member::has('batches')
        ->orderBy('full_name')
        ->select('full_name','member_no')
        ->get();
        return view('member-cards', $data);
    }
}
