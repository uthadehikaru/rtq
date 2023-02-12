<?php

namespace App\Exports;

use App\Models\Batch;
use App\Models\Member;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class BatchExport implements FromQuery, WithHeadings, WithMapping, WithTitle
{
    use Exportable;

    private Batch $batch;

    public function __construct($batch_id)
    {
        $this->batch = Batch::find($batch_id);
    }

    public function title(): string
    {
        return $this->batch->name;
    }

    public function query()
    {
        return Member::whereRelation('batches', 'id', $this->batch->id);
    }

    public function headings(): array
    {
        return [
            'nama lengkap',
            'nama panggilan',
            'jenis kelamin',
            'email',
            'notelp',
            'level',
            'sekolah',
            'kelas',
            'status',
            'alamat',
            'kodepos',
        ];
    }

    public function map($member): array
    {
        return [
            $member->full_name,
            $member->short_name,
            __('app.gender.'.$member->gender),
            $member->email,
            $member->phone,
            $member->level,
            $member->school,
            $member->class,
            $member->status,
            $member->address,
            $member->postcode,
        ];
    }
}
