<?php

namespace App\Exports;

use App\Models\Member;
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
                $query->where('period_id', $this->period->id);
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
        ];
    }

    public function map($member): array
    {
        return [
            $member->full_name,
            $member->batches->first()?->name,
            $member->paymentDetails->count() ? 'Sudah Bayar' : 'Belum Bayar',
            $member->paymentDetails->first()?->created_at,
        ];
    }
}
