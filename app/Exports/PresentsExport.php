<?php

namespace App\Exports;

use App\Models\Present;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PresentsExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public function query()
    {
        return Present::with(['schedule','user','schedule.batch'])
        ->latest();
    }

    public function headings(): array
    {
        return [
            'tanggal',
            'halaqoh',
            'nama',
            'tipe',
            'status',
            'Jam Kehadiran',
            'keterangan',
            'badal',
        ];
    }

    public function map($present): array
    {
        $isBadal = "";
        if($present->type=='teacher')
            $isBadal = $present->is_badal?'Ya':'Tidak';

        return [
            $present->schedule->scheduled_at->format('Y-m-d H:i'),
            $present->schedule->batch->name,
            $present->user->name,
            __('app.present.type.'.$present->type),
            __('app.present.status.'.$present->status),
            $present->attended_at?->format('H:i'),
            $present->description,
            $isBadal,
        ];
    }
}
