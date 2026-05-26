<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class PaymentsSheet implements FromCollection, ShouldAutoSize, WithEvents, WithHeadings, WithMapping, WithTitle
{
    use Exportable, RegistersEventListeners;

    public function __construct(
        private ?string $startDate = null,
        private ?string $endDate = null,
    ) {}

    public function title(): string
    {
        return 'Pembayaran';
    }

    public static function afterSheet(AfterSheet $event)
    {
        $worksheet = $event->sheet;
        $highestRow = $worksheet->getHighestRow();
        $attachmentColumn = array_search('Lampiran', (new self)->headings(), true) + 1;

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
            'Detail',
            'Halaqoh',
            'Nominal',
            'via',
            'Status',
            'Dikonfirmasi pada',
            'Lampiran',
            'Tujuan Transfer',
            'Keterangan',
            'ID Pembayaran',
        ];
    }

    /**
     * @var Payment
     */
    public function map($payment): array
    {
        $detailsText = $payment->details
            ->map(fn ($detail) => $detail->member->full_name.' periode '.$detail->period->name)
            ->join('; ');

        $halaqoh = $payment->details
            ->map(fn ($detail) => $detail->member->batches->pluck('name')->join(','))
            ->filter()
            ->unique()
            ->join('; ');

        return [
            $payment->created_at,
            $detailsText,
            $halaqoh,
            $payment->amount,
            $payment->payment_method,
            __('app.payment.status.'.$payment->status),
            $payment->paid_at,
            $payment->attachment ? asset('storage/'.$payment->attachment) : null,
            $payment->target_account,
            $payment->description,
            $payment->id,
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Payment::with(['details.member.batches', 'details.period']);

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        return $query->orderByDesc('created_at')->get();
    }
}
