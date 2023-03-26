<?php

namespace App\Http\Livewire;

use App\Models\Member;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use ZipArchive;

class MemberCards extends Component
{
    use WithPagination;

    public function download()
    {
        if(Storage::disk('public')->exists('cards'))
            Storage::disk('public')->deleteDirectory('cards');

        Storage::disk('public')->makeDirectory('cards');

        $zip_file = storage_path('app/public/cards/kartu anggota '.time().'.zip'); // Name of our archive to download

        $batches = Member::select('batches.name as batch','member_no')
        ->join('batch_member','members.id','batch_member.member_id')
        ->join('batches','batch_member.batch_id','batches.id')
        ->join('courses','batches.course_id','courses.id')
        ->where('courses.type','<>','Talaqqi Jamai')
        ->orderByRaw('courses.type, batches.name')
        ->get()
        ->groupBy('batch');
        // Initializing PHP class
        $zip = new ZipArchive();
        if($zip->open($zip_file, ZipArchive::CREATE)){
            foreach($batches as $batch=>$members){
                $zip -> addEmptyDir($batch); 
                foreach($members as $member){
                    if(Storage::disk('public')->exists('idcards/'.$member->member_no.'.jpg'))
                        $zip->addFile(storage_path('app/public/idcards/'.$member->member_no.'.jpg'), $batch.'/'.$member->member_no.'.jpg');
                }
            }
            $zip->close();

            // We return the file immediately after download
            return response()->download($zip_file);
        }
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
