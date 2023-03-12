<?php

namespace App\Http\Livewire;

use App\Models\Member;
use Illuminate\Support\Facades\Auth;
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

    public function updated()
    {
        $this->validate();

        $this->member->save();
    }    

    public function mount()
    {
        $this->member = Member::where('user_id', Auth::id())->first();
        $this->image = asset('storage/'.$this->member->profile_picture).'?v='.time();
    }

    public function render()
    {
        return view('livewire.member-profile-card');
    }
}
