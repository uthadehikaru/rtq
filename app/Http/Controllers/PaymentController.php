<?php

namespace App\Http\Controllers;

use App\DataTables\PaymentsDataTable;
use App\Exports\PaymentsExport;
use App\Models\Payment;
use App\Models\Period;
use App\Repositories\BatchRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\PeriodRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index(PaymentRepository $paymentRepository, PaymentsDataTable $dataTable)
    {
        $data['title'] = $paymentRepository->count().' Pembayaran';
        $data['buttons'] = '
        <div class="btn-group" role="group">
            <button id="action" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Aksi
            </button>
            <div class="dropdown-menu" aria-labelledby="action" style="">
                <a class="dropdown-item" href="'.route('periods.index').'">
                    <i class="la la-list"></i> Periode
                </a>
                <a class="dropdown-item" href="'.route('payment').'">
                    <i class="la la-plus"></i> Buat Baru
                </a>
                <a class="dropdown-item" href="'.route('payments.export').'">
                    <i class="la la-share"></i> Export to Excel
                </a>
                <a class="dropdown-item" href="'.route('periods.export').'">
                    <i class="la la-download"></i> Export per Period
                </a>
            </div>
        </div>';

        return $dataTable->render('datatables.datatable', $data);
    }

    public function form(BatchRepository $batchRepository, PeriodRepository $periodRepository, Request $request)
    {
        $data['periods'] = $periodRepository->all();
        $data['period_id'] = $request->get('period_id');

        return view('payment-confirm', $data);
    }

    public function confirm(PaymentRepository $paymentRepository, $payment_id)
    {
        $payment = $paymentRepository->find($payment_id);
        $payment->status = 'paid';
        $payment->paid_at = Carbon::now();
        $payment->save();

        return back()->with('message', __('Payment Confirmed'));
    }

    public function store(PaymentRepository $paymentRepository, Request $request)
    {
        $data = $request->validate([
            'period_ids' => 'required',
            'members' => 'required',
            'total' => 'numeric|min:120000',
            'attachment' => '',
        ]);

        $total = $paymentRepository->calculate($data);
        if ($data['total'] < $total) {
            return back()->with('error', 'Minimal nominal pembayaran '.$total);
        }

        DB::beginTransaction();

        $path = null;
        if ($request->file('attachment')) {
            $path = $request->file('attachment')->storePublicly('attachments', 'public');
        }

        $payment = $paymentRepository->create([
            'amount' => $data['total'],
            'attachment' => $path,
        ]);

        foreach ($data['period_ids'] as $period_id) {
            $period = Period::find($period_id);

            foreach ($data['members'] as $member_id) {
                if (! isset($member_id)) {
                    return back()->with('error', 'Peserta Tidak ditemukan');
                }
                $paymentDetail = $paymentRepository->check($payment, $member_id, $period_id);
                if (! $paymentDetail) {
                    $paymentRepository->createDetail([
                        'member_id' => $member_id,
                        'period_id' => $period_id,
                        'payment_id' => $payment->id,
                    ]);
                } else {
                    DB::rollBack();

                    return back()->with('error', 'Konfirmasi pembayaran sudah pernah dibuat. '.$member['value'].' '.$member['email'].' periode '.$period['name']);
                }
            }
        }
        DB::commit();

        return back()->with('message', 'Konfirmasi pembayaran telah kami terima, kami akan cek terlebih dahulu. terima kasih');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['payment'] = Payment::findOrFail($id);

        return view('forms.payment', $data);
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
        $data = $request->validate([
            'amount' => 'required|numeric',
        ]);

        Payment::find($id)->update($data);

        return to_route('payments.index')->with('message', 'Data pembayaran berhasil dipernaharui');
    }

    public function destroy(PaymentRepository $paymentRepository, $payment_id)
    {
        $status = $paymentRepository->delete($payment_id);
        $data['statusCode'] = 200;

        return response()->json($data);
    }

    public function export()
    {
        return (new PaymentsExport)->download('pembayaran per '.date('d M Y H.i').'.xlsx');
    }
}
