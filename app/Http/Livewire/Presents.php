<?php

namespace App\Http\Livewire;

use App\Models\Present;
use Laravel\Ui\Presets\Preset;
use Livewire\Component;

class Presents extends Component
{
    public $presents;
    public $statuses = [];

    protected $rules = [
        'presents.*.status' => 'required|string',
        'presents.*.description' => '',
    ];

    function __construct()
    {
        $this->statuses = Present::STATUSES;
    }

    public function updateStatus($present_id, $status)
    {
        $present = Present::find($present_id);
        $present->update(['status'=>$status]);
        $this->emit('message','Status diperbaharui');
    }

    public function updateDescription($present_id, $description)
    {
        $present = Present::find($present_id);
        if($present->description!=$description){
            $present->update(['description'=>$description]);
            $this->emit('message','Deskripsi diperbaharui');
        }
    }

    public function render()
    {
        return view('livewire.presents');
    }
}