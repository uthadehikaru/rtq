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
    public function __invoke(Request $request, MemberPaymentDataTable $dataTable)
    {
        $data['title'] = 'Rekapitulasi pembayaran';

        if ($request->has('inaktif')) {
            $dataTable->inactive();
            $data['buttons'] = '
            <a href="'.route('payments.summary').'" class="btn btn-primary">Peserta Aktif</a>
            ';
        } else {
            $data['buttons'] = '
            <a href="'.route('payments.summary', ['inaktif' => 'true']).'" class="btn btn-warning">Peserta Inaktif</a>
            ';
        }

        return $dataTable->render('datatables.datatable', $data);
    }
}
