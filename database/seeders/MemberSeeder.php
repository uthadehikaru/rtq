<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Member;
use App\Models\Batch;

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
