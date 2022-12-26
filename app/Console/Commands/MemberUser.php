<?php

namespace App\Console\Commands;

use App\Models\Member;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MemberUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'member:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create user for member';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $members = Member::all();
        $count = 0;
        $bar = $this->output->createProgressBar($members->count());

        $bar->start();
        foreach ($members as $member) {
            if (! $member->user) {
                $user = User::firstOrCreate([
                    'name' => $member->full_name,
                ], [
                    'email' => $member->full_name.'@rtqmaisuro.id',
                    'password' => Hash::make(Str::random(8)),
                ]);
                $member->update(['user_id' => $user->id]);
                $user->assignRole('member');
                $count++;
                $bar->advance();
            }
        }
        $bar->finish();
        $this->info('Created '.$count.' of '.$members->count());

        return Command::SUCCESS;
    }
}
