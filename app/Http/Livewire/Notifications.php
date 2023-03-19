<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Notifications extends Component
{
    public $notifications = [];
    public $unread = 0;

    public function mount()
    {
        $this->notifications = Auth::user()->notifications;
        $this->unread = Auth::user()->unreadNotifications->count();
    }

    public function render()
    {
        return view('livewire.notifications');
    }
}
