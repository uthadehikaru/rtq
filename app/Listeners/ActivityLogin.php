<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;

class ActivityLogin implements ShouldQueue
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
        activity()
            ->causedBy($event->user)
            ->event('auth')
            ->log(':causer.name logged in');
    }
}
