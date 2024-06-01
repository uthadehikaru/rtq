<?php

namespace App\Http\Controllers\Member;

use App\DataTables\MemberPaymentDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function __invoke(MemberPaymentDataTable $dataTable)
    {
        $data['title'] = 'Pembayaran';
        $user = Auth::user();
        $dataTable->setMember($user->member->id ?? -1);

        return $dataTable->render('datatables.datatable', $data);
    }
}
