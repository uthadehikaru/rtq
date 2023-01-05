<?php

namespace App\Exports;

use App\Models\Present;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class PresentMemberSheet implements FromQuery, WithHeadings, WithMapping, WithTitle
{
    use Exportable;

    public function title(): string
    {
        return 'Anggota';
    }

    public function query()
    {
        return Present::with(['schedule', 'user', 'schedule.batch'])
        ->where('type', 'member')
        ->latest();
    }

    public function headings(): array
    {
        return [
            'tanggal',
            'kode',
            'halaqoh',
            'durasi',
            'nama',
            'status',
            'keterangan',
        ];
    }

    public function map($present): array
    {
        return [
            $present->schedule->scheduled_at->format('Y-m-d H:i'),
            $present->schedule->batch->kode,
            $present->schedule->batch->name,
            $present->schedule->start_at?->format('H:i').' - '.$present->schedule->end_at?->format('H:i'),
            $present->user->name,
            __('app.present.status.'.$present->status),
            $present->description,
        ];
    }
}
