<?php

namespace App\Console\Commands;

use App\Models\Member;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MemberGenerateNo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'member:generateno {--reset}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Member No';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $reset = $this->option('reset');
    
        if ($reset) {
            $updated = Member::whereNotNull('member_no')->update(['member_no' => null]);
            $this->info('Reset : '.$updated);
        }

        $members = Member::whereNull('member_no')
        ->whereNotNull('birth_date')
        ->orderByRaw('birth_date, created_at')
        ->get();
        $birthDate = null;
        $no = 1;

        $bar = $this->output->createProgressBar($members->count());

        $bar->start();
        foreach ($members as $member) {
            if($reset){
                if (! $birthDate) {
                    $birthDate = $member->birth_date;
                }

                if ($birthDate != $member->birth_date) {
                    $birthDate = $member->birth_date;
                    $no = 1;
                }
            }else{
                $no = Member::whereDate('birth_date',$member->birth_date)->count();
            }

            $memberNo = 'RTQ';
            $memberNo .= $member->gender == 'male' ? '1' : '2';
            $memberNo .= $member->birth_date->format('dmy');
            $memberNo .= Str::padLeft($no++, 3, '0');
            $member->update(['member_no' => $memberNo]);

            $member->user->update(['email'=>$memberNo,'password'=>Hash::make($member->nik)]);

            $bar->advance();
        }
        $bar->finish();

        return Command::SUCCESS;
    }
}
