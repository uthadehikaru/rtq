<?php

namespace App\Exports;

use App\Models\Period;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PeriodsExport implements WithMultipleSheets
{
    use Exportable;

    public function sheets(): array
    {
        $periods = Period::orderBy('start_date')->get();

        $sheets = [];
        foreach ($periods as $period) {
            $sheets[] = new PaymentDetailsSheet($period);
        }

        return $sheets;
    }
}
