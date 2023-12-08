<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class UpdatePassword extends Component
{
    public $user;

    public $old_password;

    public $new_password;

    public $new_password_confirmation;

    protected $rules = [
        'old_password' => 'required|min:6|max:255|current_password',
        'new_password' => 'required|min:6|max:255|confirmed',
        'new_password_confirmation' => 'required|min:6|max:255',
    ];

    public function mount()
    {
        $this->user = Auth::user();
    }

    public function updated($column)
    {
        $this->validateOnly($column);
    }

    public function submitPassword()
    {
        $this->validate();

        $this->user->password = Hash::make($this->new_password);
        $this->user->save();

        return redirect()->to('dashboard')->with('message', 'Password berhasil diubah');
    }

    public function render()
    {
        return view('livewire.update-password')
        ->extends('layouts.app');
    }
}
