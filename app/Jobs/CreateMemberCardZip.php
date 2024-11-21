<?php

namespace App\Jobs;

use App\Models\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class CreateMemberCardZip implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (Storage::disk('public')->exists('cards')) {
            Storage::disk('public')->deleteDirectory('cards');
        }

        Storage::disk('public')->makeDirectory('cards');

        $filename = 'kartu anggota '.time().'.zip';
        $zip_file = storage_path('app/public/cards/'.$filename); // Name of our archive to download

        $batches = Member::select('batches.name as batch', 'member_no')
            ->join('batch_member', 'members.id', 'batch_member.member_id')
            ->join('batches', 'batch_member.batch_id', 'batches.id')
            ->join('courses', 'batches.course_id', 'courses.id')
            ->where('courses.type', '<>', 'Talaqqi Jamai')
            ->orderByRaw('courses.type, batches.name')
            ->get()
            ->groupBy('batch');

        // Initializing PHP class
        $zip = new ZipArchive();
        if ($zip->open($zip_file, ZipArchive::CREATE)) {
            foreach ($batches as $batch => $members) {
                $zip->addEmptyDir($batch);
                foreach ($members as $member) {
                    if (Storage::disk('public')->exists('idcards/'.$member->member_no.'.jpg')) {
                        $zip->addFile(storage_path('app/public/idcards/'.$member->member_no.'.jpg'), $batch.'/'.$member->member_no.'.jpg');
                    }
                }
            }
            $zip->close();

            Storage::disk('public')->move('cards/'.$filename, 'cards/kartu anggota.zip');
        }
    }
}
