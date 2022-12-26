<?php

namespace App\Exports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class MemberExport implements FromQuery, WithHeadings, WithMapping, WithTitle
{
    use Exportable;

    public function title(): string
    {
        return 'Anggota';
    }

    public function query()
    {
        return Member::with('batches')
        ->orderBy('full_name');
    }

    public function headings(): array
    {
        return [
            'id',
            'nama lengkap',
            'nama panggilan',
            'jenis kelamin',
            'level',
            'sekolah',
            'halaqoh',
        ];
    }

    public function map($member): array
    {
        return [
            $member->id,
            $member->full_name,
            $member->short_name,
            __('app.gender.'.$member->gender),
            $member->level,
            $member->school,
            $member->batch() ? $member->batch()->name : 'Inaktif',
        ];
    }
}
