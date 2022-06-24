<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class PaymentsExport implements FromCollection, WithHeadings, WithMapping, WithEvents, ShouldAutoSize
{
    use Exportable, RegistersEventListeners;

    public static function afterSheet(AfterSheet $event)
    {
        $worksheet = $event->sheet;
        // Get the highest row and column numbers referenced in the worksheet
        $highestRow = $worksheet->getHighestRow(); // e.g. 10
        $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5

        for ($row = 1; $row <= $highestRow; ++$row) {
            $value = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
            if($row>1 && $value)
                $worksheet->getCellByColumnAndRow(7, $row)->setValue('bukti transfer')->getHyperlink()->setUrl($value);
        }
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Periode',
            'Anggota',
            'Nominal',
            'Status',
            'Dikonfirmasi pada',
            'Lampiran',
        ];
    }

    /**
    * @var Payment $payment
    */
    public function map($payment): array
    {
        $members = [];
        foreach($payment->details as $detail){
            $members[] = $detail->member->full_name.' Halaqoh '.$detail->batch->name;
        }
        return [
            $payment->created_at,
            $payment->period->name,
            implode(', ',$members),
            $payment->amount,
            $payment->status,
            $payment->paid_at,
            asset('storage/'.$payment->attachment),

        ];
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Payment::with('details','details.member','details.batch')->orderBy('created_at')->get();
    }
}
