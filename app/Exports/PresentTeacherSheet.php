<?php

namespace App\Exports;

use App\Models\Present;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class PresentTeacherSheet implements FromQuery, WithHeadings, WithMapping, WithTitle
{
    use Exportable;

    private $filter = null;

    public function __construct($filter = null)
    {
        $this->filter = $filter;
    }

    public function title(): string
    {
        return 'Pengajar';
    }

    public function query()
    {
        $model = Present::with(['schedule', 'user', 'schedule.batch'])
        ->selectRaw('presents.*, (select coalesce(count(1),0) from presents p where presents.schedule_id=p.schedule_id and p.type=? and p.status=?) as present_members', ['member', 'present'])
        ->where('type', 'teacher')
        ->latest();

        if (isset($this->filter['status'])) {
            $model = $model->where('status', $this->filter['status']);
        }

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
            'tanggal',
            'kode',
            'halaqoh',
            'durasi',
            'nama',
            'kehadiran peserta',
            'status',
            'Jam Kehadiran',
            'Jam Keluar',
            'keterangan',
            'badal',
            'pengajar halaqoh',
        ];
    }

    public function map($present): array
    {
        $isBadal = $present->is_badal ? 'Ya' : 'Tidak';

        return [
            $present->schedule_id,
            $present->schedule->scheduled_at->format('Y-m-d H:i'),
            $present->schedule->batch->code,
            $present->schedule->batch->name,
            $present->schedule->start_at?->format('H:i').' - '.$present->schedule->end_at?->format('H:i'),
            $present->user->name,
            $present->present_members,
            __('app.present.status.'.$present->status),
            $present->attended_at?->format('H:i'),
            $present->leave_at?->format('H:i'),
            $present->description,
            $isBadal,
            $present->is_badal ? $present->schedule->batch->teachers->pluck('name')->join(', ') : '',
        ];
    }
}
