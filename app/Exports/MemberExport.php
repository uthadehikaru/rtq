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
            'tgl masuk',
            'no anggota',
            'nik',
            'tgl lahir',
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
            'halaqoh',
            'foto',
        ];
    }

    public function map($member): array
    {
        return [
            $member->id,
            $member->registration_date?->format('Y-m-d'),
            $member->member_no,
            $member->nik,
            $member->birth_date,
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
            $member->batches->count() ? $member->batches->pluck('name')->join(',') : 'Inaktif',
            $member->profile_picture ? asset('storage/'.$member->profile_picture) : '',
        ];
    }
}
