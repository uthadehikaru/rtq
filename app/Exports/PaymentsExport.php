<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PaymentsExport implements WithMultipleSheets
{
    use Exportable;

    public function __construct(
        private ?string $startDate = null,
        private ?string $endDate = null,
    ) {}

    public function sheets(): array
    {
        return [
            new PaymentsSheet($this->startDate, $this->endDate),
            new PaymentDetailsExportSheet($this->startDate, $this->endDate),
        ];
    }
}
