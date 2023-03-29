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

    private $filter = null;

    public function __construct($filter = null)
    {
        $this->filter = $filter;
    }

    public function title(): string
    {
        return 'Anggota';
    }

    public function query()
    {
        $model = Present::with(['schedule', 'user', 'schedule.batch'])
        ->where('type', 'member')
        ->latest();

        $model = $model->whereHas('schedule', function ($query) {
            if ($this->filter['start_date']) {
                $query->whereDate('scheduled_at', '>=', $this->filter['start_date']);
            }

            if ($this->filter['end_date']) {
                $query->whereDate('scheduled_at', '<=', $this->filter['end_date']);
            }
        });

        return $model;
    }

    public function headings(): array
    {
        return [
            'id',
            'schedule_id',
            'tanggal',
            'kode',
            'halaqoh',
            'pengajar',
            'durasi',
            'nama',
            'status',
            'operan',
            'keterangan',
        ];
    }

    public function map($present): array
    {
        return [
            $present->id,
            $present->schedule_id,
            $present->schedule->scheduled_at->format('Y-m-d H:i'),
            $present->schedule->batch->code,
            $present->schedule->batch->name,
            $present->schedule->teachers()->pluck('name')->join(', '),
            $present->schedule->start_at?->format('H:i').' - '.$present->schedule->end_at?->format('H:i'),
            $present->user->name,
            __('app.present.status.'.$present->status),
            $present->is_transfer ? 'ya' : 'tidak',
            $present->description,
        ];
    }
}
