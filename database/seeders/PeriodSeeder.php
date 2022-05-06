<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Period;
use Carbon\Carbon;

class PeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date = new Carbon('first day of January 2022', 'Asia/Jakarta');
        while($date->year==2022){
            Period::create([
                'name'=>$date->format('M Y'),
                'start_date'=>$date->startOfMonth()->format('Y-m-d'),
                'end_date'=>$date->endOfMonth()->format('Y-m-d'),
            ]);
            $date->addDay();
        }
    }
}
