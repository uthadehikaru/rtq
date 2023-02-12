<?php

namespace App\Console\Commands;

use App\Models\Schedule;
use Illuminate\Console\Command;

class ScheduleTransfer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:transfer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check transfered member';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $schedules = Schedule::latest()
        ->with(['presents', 'batch', 'presents.user', 'presents.user.member'])
        ->get();

        $bar = $this->output->createProgressBar($schedules->count());

        $bar->start();
        foreach ($schedules as $schedule) {
            if ($schedule->batch->course_id == 9) {
                continue;
            }

            foreach ($schedule->presents as $present) {
                if ($present->type == 'teacher') {
                    continue;
                }

                $memberBatch = $present->user->member->batches()->where('course_id', '<>', 9)->first();
                if ($memberBatch && $memberBatch->id != $schedule->batch_id) {
                    $present->update(['is_transfer' => true]);
                }
            }
            $bar->advance();
        }
        $bar->finish();

        return Command::SUCCESS;
    }
}
