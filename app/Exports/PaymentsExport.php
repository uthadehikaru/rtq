<?php

namespace App\Exports;

use App\Models\Payment;
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

    public static function afterSheet(AfterSheet $event)
    {
        $worksheet = $event->sheet;
        // Get the highest row and column numbers referenced in the worksheet
        $highestRow = $worksheet->getHighestRow(); // e.g. 10
        $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5

        for ($row = 1; $row <= $highestRow; $row++) {
            $value = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
            if ($row > 1 && $value) {
                $worksheet->getCellByColumnAndRow(7, $row)->setValue('bukti transfer')->getHyperlink()->setUrl($value);
            }
        }
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Periode',
            'Anggota',
            'Halaqoh',
            'Nominal',
            'Status',
            'Dikonfirmasi pada',
            'Lampiran',
        ];
    }

    /**
     * @var Payment
     */
    public function map($paymentDetail): array
    {
        return [
            $paymentDetail->created_at,
            $paymentDetail->period->name,
            $paymentDetail->member->full_name,
            $paymentDetail->member->batches()->pluck('name')->join(','),
            $paymentDetail->payment->amount,
            __('app.payment.status.'.$paymentDetail->payment->status),
            $paymentDetail->payment->paid_at,
            asset('storage/'.$paymentDetail->payment->attachment),

        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return PaymentDetail::with('payment', 'member', 'member.batches', 'period')->orderByDesc('period_id')->get();
    }
}
