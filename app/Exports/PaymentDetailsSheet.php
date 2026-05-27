<?php

namespace App\Exports;

use App\Models\Member;
use App\Models\PaymentDetail;
use App\Models\Period;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class PaymentDetailsSheet implements FromQuery, WithHeadings, WithMapping, WithTitle
{
    use Exportable;
    
    private $period;

    public function __construct(Period $period)
    {
        $this->period = $period;
    }

    public function title(): string
    {
        return $this->period->name;
    }

    public function query()
    {
        return Member::with([
            'paymentDetails' => function ($query) {
                $query->where('period_id', $this->period->id)
                    ->with(['payment' => fn ($paymentQuery) => $paymentQuery->withCount('details')]);
            },
            'batches' => function ($query) {
                $query->orderBy('name');
            },
        ])
            ->orderBy('full_name');
    }

    public function headings(): array
    {
        return [
            'nama',
            'halaqoh',
            'status',
            'tanggal bayar',
            'metode pembayaran',
            'nominal',
            'tujuan transfer',
        ];
    }

    public function map($member): array
    {
        if ($member->paymentDetails->count()) {
            $status = 'Sudah Bayar';
        } elseif(!$member->batches->first()) {
            $status = 'Inaktif';
        } elseif ($member->status) {
            $status = 'Gratis';
        } else {
            $status = 'Belum Bayar';
        }

        $paymentDetail = $member->paymentDetails->first();

        return [
            $member->full_name,
            $member->batches->first()?->name,
            $status,
            $paymentDetail?->created_at ?? '',
            $status == 'Sudah Bayar' ? $paymentDetail?->payment->payment_method ?? '' : '',
            $status == 'Sudah Bayar' && $paymentDetail ? $this->nominalForDetail($paymentDetail) : '',
            $paymentDetail?->payment->target_account ?? '',
        ];
    }

    private function nominalForDetail(PaymentDetail $detail): int
    {
        $payment = $detail->payment;
        $detailCount = $payment->details_count;

        if ($detailCount > 1) {
            return (int) round($payment->amount / $detailCount);
        }

        return $payment->amount;
    }
}
