<?php

namespace App\Http\Controllers;

use App\DataTables\PaymentDetailsDataTable;
use App\Models\Payment;
use App\Models\PaymentDetail;
use App\Models\Period;
use Illuminate\Http\Request;

class PaymentDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, PaymentDetailsDataTable $dataTable)
    {
        $data['period_id'] = $request->period_id;
        $data['period'] = null;
        $data['title'] = __('Pembayaran');
        if($request->period_id){
            $data['period'] = Period::findOrFail($request->period_id);
            $data['title'] .= ' Periode '.$data['period']->name;
            $dataTable->setPeriod($request->period_id);
        }

        return $dataTable->render('datatables.paymentdetails', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        PaymentDetail::find($id)->delete();
        $data['statusCode'] = 200;

        return response()->json($data);
    }
}
