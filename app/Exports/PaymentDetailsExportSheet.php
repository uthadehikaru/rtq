<?php

namespace App\Exports;

use App\Models\PaymentDetail;
use App\Services\SettingService;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class PaymentDetailsExportSheet implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping, WithTitle
{
    use Exportable;

    private int $memberFee = 0;

    public function __construct(
        private ?string $startDate = null,
        private ?string $endDate = null,
    ) {
        $this->memberFee = (int) (new SettingService())->value('course_fee', 0);
    }

    public function title(): string
    {
        return 'Detail Pembayaran';
    }

    public function query()
    {
        $query = PaymentDetail::with(['payment', 'period', 'member.batches', 'batch'])
            ->whereHas('payment', function ($paymentQuery) {
                if ($this->startDate) {
                    $paymentQuery->whereDate('created_at', '>=', $this->startDate);
                }

                if ($this->endDate) {
                    $paymentQuery->whereDate('created_at', '<=', $this->endDate);
                }
            });

        return $query->orderByDesc('created_at');
    }

    public function headings(): array
    {
        return [
            'Tanggal dibuat',
            'Nama peserta',
            'Periode',
            'Halaqoh',
            'Nominal',
            'via',
            'Status',
            'Dikonfirmasi pada',
            'Tujuan Transfer',
            'ID Pembayaran',
        ];
    }

    /**
     * @var PaymentDetail
     */
    public function map($detail): array
    {
        $halaqoh = $detail->member->batches->pluck('name')->join(', ')
            ?: $detail->batch?->name;

        return [
            $detail->payment->created_at,
            $detail->member->full_name,
            $detail->period->name,
            $halaqoh,
            $this->memberFee,
            $detail->payment->payment_method,
            __('app.payment.status.'.$detail->payment->status),
            $detail->payment->paid_at,
            $detail->payment->target_account,
            $detail->payment_id,
        ];
    }
}
