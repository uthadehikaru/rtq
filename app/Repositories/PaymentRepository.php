<?php

namespace App\Repositories;

use App\Interfaces\PaymentRepositoryInterface;
use App\Models\Payment;
use App\Models\PaymentDetail;

class PaymentRepository implements PaymentRepositoryInterface
{
    public function all()
    {
        return Payment::with(['details.member', 'details.batch'])->get();
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
        return Payment::findOrFail($id);
    }

    public function delete($id)
    {
        Payment::destroy($id);
    }

    public function create(array $data)
    {
        return Payment::create($data);
    }

    public function update($id, array $data)
    {
        return Payment::whereId($id)->update($data);
    }

    public function check($payment, $batch_id, $member_id)
    {
        return PaymentDetail::where([
            'batch_id' => $batch_id,
            'member_id' => $member_id,
        ])
        ->whereExists(function ($query) use ($payment) {
            return $query->selectRaw('count(1)')
            ->from('payments')->whereColumn('payments.id', 'payment_details.payment_id')
            ->where('payments.period_id', $payment->period_id);
        })
        ->first();
    }

    public function createDetail(array $data)
    {
        return PaymentDetail::create($data);
    }
}
