<?php

namespace App\Listeners;

use App\Events\MemberActivated;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogMemberActivated implements ShouldQueue
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
    public function handle(MemberActivated $event)
    {
        $member = $event->member;
        
        activity()
            ->on($member)
            ->event('halaqoh')
            ->log(':subject.full_name masuk halaqoh '.$member->batches->pluck('name')->join(',').' pada '.$member->registration_date->format('l, d M Y'));

    }
}