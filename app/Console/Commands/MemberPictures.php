<?php

namespace App\Console\Commands;

use App\Models\Member;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class MemberPictures extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'member:pictures';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate thumbnail of member pictures';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $members = Member::whereNotNull('profile_picture')->get();

        $bar = $this->output->createProgressBar($members->count());

        $bar->start();
        foreach ($members as $member) {
            thumbnail($member->profile_picture, 300, 400, true);
            $bar->advance();
        }
        $bar->finish();

        return Command::SUCCESS;
    }
}
