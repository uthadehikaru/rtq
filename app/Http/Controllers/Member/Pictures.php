<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class Pictures extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $data['members'] = Member::has('batches')->orderBy('full_name')->select('full_name', 'profile_picture')->paginate(24);

        return view('member-pictures', $data);
    }
}
