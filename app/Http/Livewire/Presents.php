<?php

namespace App\Http\Livewire;

use App\Models\Present;
use Livewire\Component;

class Presents extends Component
{
    public $presents;

    public $statuses = [];

    protected $rules = [
        'presents.*.status' => 'required|string',
        'presents.*.description' => '',
    ];

    public function __construct()
    {
        $this->statuses = Present::STATUSES;
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
