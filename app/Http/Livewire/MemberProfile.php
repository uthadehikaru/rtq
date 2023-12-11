<?php

namespace App\Http\Livewire;

use App\Events\ProfileUpdated;
use Carbon\Carbon;
use finfo;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
            $filename = $this->member->profile_picture;
            if ($filename) {
                $thumbnail = 'thumbnail/'.basename($filename);
                Storage::disk('public')->delete('thumbnails/'.$thumbnail);
            } else {
                $filename .= 'profiles/'.Str::random().'.jpg';
                $this->member->profile_picture = $filename;
            }
            $this->member->updated_at = Carbon::now();
            $changeColumn = $this->member->getDirty();
            $this->member->save();
            $image->save(storage_path('app/public/'.$filename));
            Artisan::call('member:card', ['--no' => $this->member->member_no]);
            ProfileUpdated::dispatch($this->member, $changeColumn);
            $this->emit('refresh');
        }
    }

    public function render()
    {
        return view('livewire.member-profile');
    }
}
