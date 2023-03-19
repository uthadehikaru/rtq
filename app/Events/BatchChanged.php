<?php

namespace App\Events;

use App\Models\Member;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BatchChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $member;

    public $oldBatch;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Member $member, $oldBatch)
    {
        $this->member = $member;
        $this->oldBatch = $oldBatch;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
