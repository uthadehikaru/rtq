<?php

namespace App\Http\Controllers;

use App\DataTables\PaymentsDataTable;
use App\Exports\PaymentsExport;
use App\Models\Payment;
use App\Models\Period;
use App\Models\User;
use App\Notifications\PaymentConfirmation;
use App\Repositories\BatchRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\PeriodRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class PaymentController extends Controller
{
    public function index(PaymentRepository $paymentRepository, PaymentsDataTable $dataTable)
    {
        $data['title'] = $paymentRepository->count().' Pembayaran';

        return $dataTable->render('datatables.payment', $data);
    }

    public function form(BatchRepository $batchRepository, PeriodRepository $periodRepository, Request $request)
    {
        $data['periods'] = $periodRepository->lastSixMonth();
        $data['period_id'] = $request->get('period_id');

        return view('payment-confirm', $data);
    }

    public function confirm(PaymentRepository $paymentRepository, $payment_id)
    {
        $paymentRepository->confirm($payment_id);

        return back()->with('message', __('Payment Confirmed'));
    }

    public function store(PaymentRepository $paymentRepository, Request $request)
    {
        $data = $request->validate([
            'period_ids' => 'required',
            'members' => 'required',
            'total' => 'numeric|min:120000',
            'attachment' => 'required|image',
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
            foreach ($data['members'] as $member_id) {
                if (! isset($member_id)) {
                    return back()->with('error', 'Peserta Tidak ditemukan');
                }
                $paymentDetail = $paymentRepository->check($member_id, $period_id);
                if (! $paymentDetail) {
                    $paymentRepository->createDetail([
                        'member_id' => $member_id,
                        'period_id' => $period_id,
                        'payment_id' => $payment->id,
                    ]);
                } else {
                    DB::rollBack();

                    return back()->with('error', 'Konfirmasi pembayaran sudah pernah dibuat. '.$paymentDetail->member->full_name.' periode '.$paymentDetail->period->name);
                }
            }
        }

        $admins = User::role('administrator')->notify()->get();
        Notification::send($admins, new PaymentConfirmation($payment));
        activity()
            ->on($payment)
            ->event('payment')
            ->log('Konfirmasi Pembayaran sebesar :subject.amount dari '.$request->ip());

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
        $data['payment'] = Payment::with('details')->findOrFail($id);
        $data['periods'] = Period::orderBy('name')->get();

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
            'paid_at' => 'nullable|date',
            'payment_method' => 'required|in:transfer,amplop',
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
