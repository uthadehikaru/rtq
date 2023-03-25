<?php

namespace App\Listeners;

use App\Events\ProfileUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogProfileUpdated implements ShouldQueue
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
     * @param  \App\Events\MemberActivated  $event
     * @return void
     */
    public function handle(ProfileUpdated $event)
    {
        activity()
            ->on($event->member)
            ->event('member')
            ->withProperties($event->changeColumns)
            ->log(':subject.full_name memperbaharui biodata diri');
    }
}
