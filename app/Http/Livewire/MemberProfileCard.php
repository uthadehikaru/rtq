<?php

namespace App\Http\Livewire;

use App\Events\ProfileUpdated;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class MemberProfileCard extends Component
{
    public $member;

    public $image;

    protected $rules = [
        'member.short_name' => 'required|min:3',
        'member.email' => 'required|email',
        'member.phone' => 'required|numeric|min:6',
        'member.address' => '',
        'member.postcode' => '',
    ];

    public function simpan()
    {
        $this->validate();
        if ($this->member->isDirty()) {
            ProfileUpdated::dispatch($this->member, $this->member->getDirty());
            $this->member->save();
        }
        $this->setErrorBag(['message'=>'Data berhasil disimpan']);
    }

    public function mount($member)
    {
        $this->member = $member;
        if ($this->member->profile_picture && Storage::disk('public')->exists($this->member->profile_picture)) {
            $this->image = asset('storage/'.$this->member->profile_picture).'?v='.time();
        } else {
            $this->image = asset('assets/images/default.jpg');
        }
    }

    public function render()
    {
        return view('livewire.member-profile-card');
    }
}
