<?php

namespace App\Http\Livewire;

use App\Models\Present;
use Livewire\Component;

class Presents extends Component
{
    public $presents;

    public $schedule_id;

    public $statuses = [];

    protected $rules = [
        'presents.*.status' => 'required|string',
        'presents.*.description' => '',
    ];

    protected $listeners = ['member-present-updated' => 'refresh'];

    public function __construct()
    {
        $this->statuses = Present::STATUSES;
    }

    public function refresh()
    {
        $this->presents = Present::with('user')->where('schedule_id', $this->schedule_id)->get();
    }

    public function remove($present_id)
    {
        Present::find($present_id)->delete();
        $this->refresh();
    }

    public function updateStatus($present_id, $status)
    {
        $present = Present::find($present_id);
        $present->update(['status' => $status]);
        $this->emit('message', $present_id);
    }

    public function updateDescription($present_id, $description)
    {
        $present = Present::find($present_id);
        if ($present->description != $description) {
            $present->update(['description' => $description]);
            $this->emit('message', $present_id);
        }
    }

    public function render()
    {
        return view('livewire.presents');
    }
}
