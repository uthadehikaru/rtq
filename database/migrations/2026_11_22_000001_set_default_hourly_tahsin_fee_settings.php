<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Tahsin fees on the salary config are now an hourly rate multiplied by the
 * class hours for its size. Fill in 50000/hour (the rate the previous
 * hardcoded amounts were based on) where the setting is missing or empty.
 */
return new class extends Migration
{
    private $names = ['tahsin_anak', 'tahsin_dewasa', 'tahsin_balita'];

    private $hourlyFee = 50000;

    public function up()
    {
        foreach ($this->names as $name) {
            $setting = DB::table('settings')
                ->where('group', 'salary')
                ->where('name', $name)
                ->first();

            if (! $setting) {
                DB::table('settings')->insert([
                    'group' => 'salary',
                    'name' => $name,
                    'payload' => json_encode($this->hourlyFee),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                continue;
            }

            if (empty(json_decode($setting->payload, true))) {
                DB::table('settings')
                    ->where('id', $setting->id)
                    ->update([
                        'payload' => json_encode($this->hourlyFee),
                        'updated_at' => now(),
                    ]);
            }
        }
    }

    public function down()
    {
        // Configured fees are left as they are.
    }
};
