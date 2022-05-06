<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\BatchRepositoryInterface;
use App\Interfaces\PaymentRepositoryInterface;
use App\Interfaces\PeriodRepositoryInterface;
use App\Interfaces\MemberRepositoryInterface;
use Carbon\Carbon;
use DB;

class PaymentController extends Controller
{
    public function index(PeriodRepositoryInterface $periodRepository, PaymentRepositoryInterface $paymentRepository)
    {
        $data['title'] = __('Payments');
        $data['payments'] = $paymentRepository->all();
        $data['total'] = $paymentRepository->count();
        return view('datatables.payment', $data);
    }

    public function form(BatchRepositoryInterface $batchRepository, PeriodRepositoryInterface $periodRepository, Request $request)
    {
        $data['members'] = $batchRepository->getBatchMembers();
        $data['periods'] = $periodRepository->all();
        $data['period_id'] = $request->get('period_id');
        return view('payment-confirm', $data);
    }

    public function confirm(PaymentRepositoryInterface $paymentRepository, $payment_id)
    {
        $payment = $paymentRepository->find($payment_id);
        $payment->status='paid';
        $payment->paid_at=Carbon::now();
        $payment->save();
        return back()->with('message',__('Payment Confirmed'));
    }

    public function store(BatchRepositoryInterface $batchRepository, MemberRepositoryInterface $memberRepository, PaymentRepositoryInterface $paymentRepository, Request $request)
    {
        $data = $request->validate([
            'period_id'=>'required',
            'members'=>'required',
            'attachment'=>'',
        ]);
        DB::beginTransaction();

        $members = json_decode($data['members'], true);

        foreach($members as $member){
            $ids = explode('_',$member['id']);
            $batch_id = $ids[0];
            $member_id = $ids[1];
            $payment = $paymentRepository->check($data['period_id'], $batch_id, $member_id);
            if(!$payment){
                $batch = $batchRepository->find($batch_id);
                $path = $request->file('attachment')->storePublicly('attachments', 'public');
                $payment = $paymentRepository->create([
                    'period_id'=>$data['period_id'],
                    'batch_id'=>$batch_id,
                    'member_id'=>$member_id,
                    'amount'=>$batch->course->fee,
                    'attachment'=>$path,
                ]);
            }else{
                DB::rollBack();
                return back()->with('error','Payment already exists. '.$member['value'].' '.$member['email']);
            }
        }
        DB::commit();
        return back()->with('message','Payment Confirmation Added, Thank You');
    }

    public function destroy(PaymentRepositoryInterface $paymentRepository, $payment_id)
    {
        $status = $paymentRepository->delete($payment_id);
        $data['statusCode'] = 200;
        return response()->json($data);
    }
}
