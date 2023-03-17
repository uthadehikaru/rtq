<?php

namespace App\Http\Livewire;

use finfo;
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
        if($img){
            $imagePath = storage_path('app/public/'.$img);
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $type = $finfo->file($imagePath);
            $this->profile_picture = 'data:' . $type . ';base64,' . base64_encode(file_get_contents($imagePath));
        }
    }

    public function updated($property)
    {
        if($property=='profile_picture'){
            $image = Image::make($this->profile_picture);
            $image->save(storage_path('app/public/'.$this->member->profile_picture));
            $this->member->save();
        }
    }

    public function render()
    {
        return view('livewire.member-profile');
    }
}
