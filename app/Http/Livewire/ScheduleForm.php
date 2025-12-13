<?php

namespace App\Http\Livewire;

use App\Models\Batch;
use Livewire\Component;
use App\Repositories\ScheduleRepository;

use function Symfony\Component\Translation\t;

class ScheduleForm extends Component
{
    public $scheduled_at;
    public $batch_id;
    public $start_at;
    public $place;

    public $batches;

    public function mount()
    {
        $this->scheduled_at = now()->format('Y-m-d');
        $this->batches = Batch::active()->orderBy('name')->get();
    }

    public function updatedBatchId($batch_id)
    {
        $batch = $this->batches->find($batch_id);
        $this->start_at = $batch->start_time->format('H:i');
        $this->place = $batch->place;
    }

    public function store()
    {
        $this->validate([
            'scheduled_at' => 'required|date',
            'batch_id' => 'required',
            'start_at' => 'required',
            'place' => 'required',
        ]);

        $schedule = (new ScheduleRepository)->create([
            'scheduled_at' => $this->scheduled_at,
            'batch_id' => $this->batch_id,
            'start_at' => $this->start_at,
            'place' => $this->place,
            'teacher_ids' => [],
        ]);

        return redirect()->route('schedules.index')->with('message', __('Created Successfully'));
    }

    public function render()
    {
        return view('livewire.schedule-form')
        ->extends('layouts.app')
        ->section('content');
    }
}
