<?php

namespace App\Http\Controllers\Schedule;

use App\Exports\PresentsExport;
use App\Http\Controllers\Controller;

class Export extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        return (new PresentsExport)->download('rekap kehadiran per '.date('d M Y H.i').'.xlsx');
    }
}
