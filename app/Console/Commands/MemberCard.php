<?php

namespace App\Console\Commands;

use App\Models\Member;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MemberCard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'member:card {--limit=0} {--no=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Member Card';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $limit = $this->option('limit');
        $no = $this->option('no');

        Storage::disk('public')->makeDirectory('idcards');

        $members = Member::whereNotNull('member_no')
            ->orderBy('member_no');

        if ($no) {
            $members = $members->where('member_no', $no);
        }

        if ($limit > 0) {
            $members = $members->limit($limit);
        }

        $members = $members->get();

        $bar = $this->output->createProgressBar($members->count());

        $template = Storage::disk('public')->get('uploads/'.basename(setting('idcard')));
        if (! $template) {
            return $this->error('No Template Found');
        }

        $bar->start();
        foreach ($members as $member) {
            $image = Image::make($template);
            if ($member->profile_picture && Storage::disk('public')->exists($member->profile_picture)) {
                $profile = Storage::disk('public')->get($member->profile_picture);
                $watermark = Image::make($profile);
                $watermark->resize(250, 250);
                $image->insert($watermark, 'top-left', 10, 240);
            } else {
                $watermark = Image::make(public_path('assets/images/default.jpg'));
                $watermark->resize(250, 250);
                $image->insert($watermark, 'top-left', 10, 240);
            }
            $image->rectangle(715, 195, 925, 495, function ($draw) {
                $draw->background('#f3f4df');
            });
            $image->text($member->full_name, 400, 225, function ($font) {
                $font->file(public_path('assets/fonts/Roboto-Regular.ttf'));
                $font->size(40);
                $font->align('left');
                $font->valign('top');
            });
            $image->text($member->member_no, 400, 300, function ($font) {
                $font->file(public_path('assets/fonts/Roboto-Regular.ttf'));
                $font->size(40);
                $font->align('left');
                $font->valign('top');
            });
            $qrcode = Image::make(QrCode::format('png')->size(210)->generate(url('login', ['username' => $member->member_no]))->toHtml());
            $image->insert($qrcode, 'top-left', 715, 285);
            $image->save(storage_path('app/public/idcards/'.$member->member_no.'.jpg'));
            $bar->advance();
        }
        $bar->finish();

        return Command::SUCCESS;
    }
}
