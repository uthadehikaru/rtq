<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Intervention\Image\Facades\Image;

class MemberProfile extends Component
{
    public $member;
    public $profile_picture;

    public function mount()
    {
        $img = $this->member?->profile_picture;
        $this->profile_picture = thumbnail($img);
    }

    public function rotate()
    {
        $path = Storage::disk('public')->get($this->member->profile_picture);
        $image = Image::make($path)->rotate(-90);
        $image->save(storage_path('app/public/'.$this->member->profile_picture));
        $this->member->save();
        $this->profile_picture = asset('storage/'.$this->member->profile_picture).'?v='.time();
    }

    public function render()
    {
        return view('livewire.member-profile');
    }
}
