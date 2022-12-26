<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Models\Present;

class RemoveMemberFromSchedule extends Controller
{
    public function __invoke($schedule_id, $present_id)
    {
        Present::find($present_id)->delete();

        return back()->with('message', 'Peserta berhasil dihapus');
    }
}
