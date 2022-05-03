<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\MemberRepositoryInterface;

class MemberController extends Controller
{
    public function index(MemberRepositoryInterface $memberRepository)
    {
        $data['title'] = __('Members');
        $data['members'] = $memberRepository->all();
        $data['total'] = $memberRepository->count();
        return view('member', $data);
    }
}
