<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Notifications extends Component
{
    public $notifications = [];

    public $unread = 0;

    public function mount()
    {
        $this->update();
    }

    private function update()
    {
        $this->notifications = Auth::user()->unreadNotifications;
        $this->unread = $this->notifications->count();
    }

    public function markAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        $this->update();
    }

    public function render()
    {
        return view('livewire.notifications');
    }
}
