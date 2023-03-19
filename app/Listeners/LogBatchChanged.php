<?php

namespace App\Listeners;

use App\Events\BatchChanged;
use App\Events\MemberActivated;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogBatchChanged implements ShouldQueue
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
    public function handle(BatchChanged $event)
    {
        $member = $event->member;
        
        activity()
            ->on($member)
            ->event('halaqoh')
            ->log(':subject.full_name pindah dari '.$event->oldBatch.' ke '.$member->batches->pluck('name')->join(','));

    }
}
