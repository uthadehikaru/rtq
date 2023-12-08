<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Profile extends Component
{
    public $user;

    public $rules = [
        'user.name' => 'required|min:3',
        'user.email' => 'required|email',
        'user.is_notify' => '',
    ];

    public function mount()
    {
        $this->user = Auth::user();
    }

    public function update()
    {
        $this->validate();
        $this->user->save();
        session()->flash('message', 'Profile berhasil diperbaharui');
    }

    public function render()
    {
    return view('livewire.profile');
    }
}
