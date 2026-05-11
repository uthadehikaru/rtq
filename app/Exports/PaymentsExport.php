<?php

namespace App\Exports;

use App\Models\PaymentDetail;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class PaymentsExport implements FromCollection, ShouldAutoSize, WithEvents, WithHeadings, WithMapping
{
    use Exportable, RegistersEventListeners;

    public function __construct(
        private ?string $startDate = null,
        private ?string $endDate = null,
    ) {}

    public static function afterSheet(AfterSheet $event)
    {
        $worksheet = $event->sheet;
        $highestRow = $worksheet->getHighestRow();
        $attachmentColumn = count((new self)->headings());

        for ($row = 2; $row <= $highestRow; $row++) {
            $value = $worksheet->getCellByColumnAndRow($attachmentColumn, $row)->getValue();
            if ($value) {
                $worksheet->getCellByColumnAndRow($attachmentColumn, $row)
                    ->setValue('bukti transfer')
                    ->getHyperlink()
                    ->setUrl($value);
            }
        }
    }

    public function headings(): array
    {
        return [
            'Tanggal dibuat',
            'Periode',
            'Anggota',
            'Halaqoh',
            'Nominal',
            'via',
            'Status',
            'Dikonfirmasi pada',
            'Lampiran',
        ];
    }

    /**
     * @var PaymentDetail
     */
    public function map($paymentDetail): array
    {
        return [
            $paymentDetail->created_at,
            $paymentDetail->period->name,
            $paymentDetail->member->full_name,
            $paymentDetail->member->batches()->pluck('name')->join(','),
            $paymentDetail->payment->amount,
            $paymentDetail->payment->payment_method,
            __('app.payment.status.'.$paymentDetail->payment->status),
            $paymentDetail->payment->paid_at,
            $paymentDetail->payment->attachment ? asset('storage/'.$paymentDetail->payment->attachment) : null,
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = PaymentDetail::with('payment', 'member', 'member.batches', 'period');

        $query->whereHas('payment', function ($query) {
            if ($this->startDate) {
                $query->whereDate('created_at', '>=', $this->startDate);
            }

            if ($this->endDate) {
                $query->whereDate('created_at', '<=', $this->endDate);
            }
        });

        return $query->orderByDesc('period_id')->get();
    }
}
