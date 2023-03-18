<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use finfo;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Livewire\Component;

class MemberProfile extends Component
{
    public $member;

    public $profile_picture;

    public function mount()
    {
        $img = $this->member?->profile_picture;
        if ($img && Storage::disk('public')->exists($img)) {
            $imagePath = storage_path('app/public/'.$img);
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $type = $finfo->file($imagePath);
            $this->profile_picture = 'data:'.$type.';base64,'.base64_encode(file_get_contents($imagePath));
        }
    }

    public function updated($property)
    {
        if ($property == 'profile_picture') {
            $image = Image::make($this->profile_picture);
            $image->save(storage_path('app/public/'.$this->member->profile_picture));
            $thumbnail = 'thumbnail/'.basename($this->member->profile_picture);
            Storage::disk('public')->delete('thumbnails/'.$thumbnail);
            Artisan::call('member:card', ['--no' => $this->member->member_no]);
            $this->member->updated_at = Carbon::now();
            $this->member->save();
            $this->emit('refresh');
        }
    }

    public function render()
    {
        return view('livewire.member-profile');
    }
}
