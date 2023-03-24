<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\BiodataUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifyBiodataUpdated implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $admins = User::role('administrator')->get();
        Notification::send($admins, new BiodataUpdated($event->member, $event->biodata));
    }
}
