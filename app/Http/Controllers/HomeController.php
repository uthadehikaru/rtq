<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\CourseRepositoryInterface;
use App\Interfaces\BatchRepositoryInterface;
use App\Interfaces\MemberRepositoryInterface;
use Auth;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function dashboard(CourseRepositoryInterface $courseRepository, BatchRepositoryInterface $batchRepository, MemberRepositoryInterface $memberRepository)
    {
        $data['title'] = __('Dashboard');
        $data['courses'] = $courseRepository->count();
        $data['batches'] = $batchRepository->count();
        $data['members'] = $memberRepository->count();
        return view('dashboard', $data);
    }
}
