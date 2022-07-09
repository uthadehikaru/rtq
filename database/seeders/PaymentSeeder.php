<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Period;
use DB;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $periods = Period::all();
        $members = DB::table('batch_member')->take(rand(5, 10))->get();
        $statuses = ['new', 'paid'];

        foreach ($periods as $period) {
            foreach ($members as $member) {
                $status = array_rand($statuses);
                $paid_at = null;
                if ($statuses[$status] == 'paid') {
                    $paid_at = $period->start_date->addDays(rand(5, 10));
                }
                Payment::create([
                    'period_id' => $period->id,
                    'member_id' => $member->member_id,
                    'batch_id' => $member->batch_id,
                    'amount' => rand(100000, 500000),
                    'paid_at' => $paid_at,
                    'status' => $statuses[$status],
                ]);
            }
        }
    }
}
