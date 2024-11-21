<?php

namespace App\Http\Livewire;

use App\Jobs\CreateMemberCardZip;
use App\Models\Member;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class MemberCards extends Component
{
    use WithPagination;

    public $file_zip = null;

    public function create()
    {
        CreateMemberCardZip::dispatch();
    }

    public function check()
    {
        if (Storage::disk('public')->exists('cards/kartu anggota.zip')) {
            $this->file_zip = storage_path('app/public/cards/kartu anggota.zip');
        }
    }

    public function download()
    {
        return response()->download($this->file_zip);
    }

    public function render()
    {
        $data['members'] = Member::has('batches')
            ->orderBy('full_name')
            ->select('full_name', 'member_no')
            ->simplePaginate(24);

        return view('livewire.member-cards', $data)
            ->extends('layouts.app')
            ->section('content');
    }
}
