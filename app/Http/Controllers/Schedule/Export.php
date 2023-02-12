<?php

namespace App\Http\Controllers\Schedule;

use App\Exports\PresentsExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Export extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $filter = $request->only(['type', 'start_date', 'end_date']);
        $export = new PresentsExport($filter);
        $name = isset($filter['type']) ? ' '.__($filter['type']) : '';
        $name .= isset($filter['start_date']) ? ' tanggal '.$filter['start_date'] : '';
        $name .= isset($filter['end_date']) ? ' sampai '.$filter['end_date'] : '';

        return $export->download('rekap kehadiran'.$name.'.xlsx');
    }
}
