<?php

namespace App\Http\Controllers\Payment;

use App\DataTables\MemberPaymentDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Rekapitulasi extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(MemberPaymentDataTable $dataTable)
    {
        $data['title'] = 'Rekapitulasi pembayaran';
        return $dataTable->render('datatables.datatable', $data);
    }
}
