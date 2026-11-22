<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private $settings = [
        'oper_santri_tahsin_anak' => 10000,
        'oper_santri_tahsin_dewasa' => 12000,
        'oper_santri_tahsin_balita' => 10000,
    ];

    public function up()
    {
        foreach ($this->settings as $name => $amount) {
            $exists = DB::table('settings')
                ->where('group', 'salary')
                ->where('name', $name)
                ->exists();

            if (! $exists) {
                DB::table('settings')->insert([
                    'group' => 'salary',
                    'name' => $name,
                    'payload' => json_encode($amount),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down()
    {
        DB::table('settings')
            ->where('group', 'salary')
            ->whereIn('name', array_keys($this->settings))
            ->delete();
    }
};
