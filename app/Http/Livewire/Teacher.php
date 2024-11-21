<?php

namespace App\Http\Livewire;

use App\Models\Present;
use App\Models\Schedule;
use App\Services\SettingService;
use Livewire\Component;

class Teacher extends Component
{
    public $present;

    public $schedule;

    public $statuses;

    public $presentCount = 0;

    public $duration = 0;

    protected $listeners = ['message' => 'recalculate'];

    public function __construct()
    {
        $this->statuses = Present::STATUSES;
    }

    public function mount()
    {
        $this->duration = (new SettingService)->value('durasi_'.str($this->schedule->batch->course->type)->snake(), 0);
        $this->presentCount = $this->schedule->presents()->present()->count();
    }

    public function recalculate()
    {
        $this->presentCount = $this->schedule->presents()->present()->count();
    }

    public function updateDescription($present_id, $description)
    {
        $present = Present::find($present_id);
        if ($present->description != $description) {
            $present->update(['description' => $description]);
            $this->emit('message', 'Deskripsi diperbaharui');
        }
    }

    public function updatePlace($schedule_id, $place)
    {
        $schedule = Schedule::find($schedule_id);
        if ($schedule->place != $place) {
            $schedule->update(['place' => $place]);
            $this->emit('message', 'Tempat diperbaharui');
        }
    }

    public function render()
    {
        return view('livewire.teacher');
    }
}
