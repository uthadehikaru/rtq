<?php

namespace App\Http\Controllers;

use App\Interfaces\BatchRepositoryInterface;
use App\Interfaces\MemberRepositoryInterface;
use App\Interfaces\PaymentRepositoryInterface;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function dashboard(PaymentRepositoryInterface $paymentRepository, BatchRepositoryInterface $batchRepository, MemberRepositoryInterface $memberRepository)
    {
        $data['title'] = __('Dashboard');
        $data['payments'] = $paymentRepository->count();
        $data['batches'] = $batchRepository->count();
        $data['members'] = $memberRepository->count();

        return view('dashboard', $data);
    }
}
