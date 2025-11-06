<?php

namespace App\Http\Livewire;

use Livewire\Component;

class MemberScan extends Component
{
    public $schedule_id;

    public function render()
    {
        return view('livewire.member-scan');
    }
}
