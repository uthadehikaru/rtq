<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PresentsExport implements WithMultipleSheets
{
    use Exportable;

    public function sheets(): array
    {
        return [
            new PresentTeacherSheet(),
            new PresentMemberSheet(),
        ];
    }    
}
