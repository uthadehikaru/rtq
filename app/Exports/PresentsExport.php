<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PresentsExport implements WithMultipleSheets
{
    use Exportable;

    private $filter = null;

    public function __construct($filter = null)
    {
        $this->filter = $filter;
    }

    public function sheets(): array
    {
        $sheets = [];

        if (isset($this->filter['type'])) {
            switch ($this->filter['type']) {
                case 'teacher':
                    $sheets[] = new PresentTeacherSheet($this->filter);
                    break;
                case 'member':
                    $sheets[] = new PresentMemberSheet($this->filter);
                    break;
            }
        } else {
            $sheets[] = new PresentTeacherSheet($this->filter);
            $sheets[] = new PresentMemberSheet($this->filter);
        }

        return $sheets;
    }
}
