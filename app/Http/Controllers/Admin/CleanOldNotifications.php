<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class CleanOldNotifications extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        $count = DatabaseNotification::whereNotNull('read_at')->whereDate('created_at', '<=', Carbon::now()->subDays(7))->delete();

        return back()->with('message', 'Deleted '.$count);
    }
}
