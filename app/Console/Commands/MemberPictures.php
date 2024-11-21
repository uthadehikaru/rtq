<?php

namespace App\Console\Commands;

use App\Models\Member;
use Exception;
use Illuminate\Console\Command;
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
        $members = Member::whereNotNull('profile_picture')->latest()->get();

        $bar = $this->output->createProgressBar($members->count());

        $bar->start();
        foreach ($members as $member) {
            try {
                $filename = basename($member->profile_picture);
                $path = storage_path('app/public/profiles/'.$filename);
                if (! file_exists($path)) {
                    continue;
                }

                $image = Image::make($path)->resize(800, 1024, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $image->orientate();
                if ($image->width() > $image->height()) {
                    $image->rotate(-90);
                }
                $new = rand().'.jpg';
                $image->save(storage_path('app/public/profiles/'.$new));
                $member->update(['profile_picture' => 'profiles/'.$new]);
                unlink(storage_path('app/public/profiles/'.$filename));
            } catch (Exception $ex) {
                $this->warn('Failed '.$member->full_name.' : '.$member->profile_picture);
                $this->error($ex->getMessage());
            }
            $bar->advance();
        }
        $bar->finish();

        return Command::SUCCESS;
    }
}
