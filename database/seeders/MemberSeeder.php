<?php

namespace Database\Seeders;

use App\Models\Batch;
use App\Models\Member;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $batches = Batch::all();

        Member::factory()
            ->count(50)
            ->hasAttached($batches)
            ->create();
    }
}
