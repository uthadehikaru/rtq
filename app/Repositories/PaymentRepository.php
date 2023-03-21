<?php

namespace App\Repositories;

use App\Interfaces\PaymentRepositoryInterface;
use App\Models\Payment;
use App\Models\PaymentDetail;
use App\Notifications\PaymentConfirmed;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class PaymentRepository implements PaymentRepositoryInterface
{
    public function all()
    {
        return Payment::latest()->with(['details.member', 'details.batch'])->get();
    }

    public function count()
    {
        return Payment::count();
    }

    public function getByPeriod($period_id)
    {
        return Payment::where('period_id', $period_id)->get();
    }

    public function countByPeriod($period_id)
    {
        return Payment::where('period_id', $period_id)->count();
    }

    public function find($id)
    {
        return Payment::with(['details', 'details.period', 'details.member'])->findOrFail($id);
    }

    public function delete($id)
    {
        Payment::destroy($id);
    }

    public function create(array $data)
    {
        return Payment::create($data);
    }

    public function createPayment(array $data)
    {
        $payment = Payment::create($data);

        foreach($data['details'] as $detail){
            $detail['payment_id'] = $payment->id;
            PaymentDetail::create($detail);
        }

        return $payment;
    }

    public function update($id, array $data)
    {
        return Payment::whereId($id)->update($data);
    }

    public function check($member_id, $period_id)
    {
        $detail = PaymentDetail::where([
            'member_id' => $member_id,
            'period_id' => $period_id,
        ])
        ->first();

        if($detail)
            $detail->load(['member','period']);

        return $detail;
    }

    public function createDetail(array $data)
    {
        return PaymentDetail::create($data);
    }

    public function calculate($data): int
    {
        $total = 0;
        foreach ($data['period_ids'] as $period_id) {
            foreach ($data['members'] as $member) {
                $total += 120000;
            }
        }

        return $total;
    }

    public function confirm($payment_id)
    {
        DB::beginTransaction();

        $payment = $this->find($payment_id);
        $payment->status = 'paid';
        $payment->paid_at = Carbon::now();
        $payment->save();

        foreach ($payment->details as $detail) {
            $user = $detail->member->user;
            if ($user) {
                Notification::send($user, new PaymentConfirmed($detail));
            }
        }

        DB::commit();

        return $payment;
    }
}
