<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            'oper_santri',
            'transportasi',
            'tunjangan',
            'telat_tanpa_konfirmasi',
            'maks_telat_dengan_konfirmasi',
            'pengurangan_tunjangan_per_izin',
            'maks_izin',
        ];

        foreach(Course::TYPES as $type){
            $settings[] = Str::snake($type);
        }

        foreach($settings as $setting){
            Setting::firstOrCreate([
                'group'=>'salary',
                'name'=>$setting,
            ],[
                'payload'=>0,
            ]);
        }

        $homepage = [
            'tagline'=>"Hidup Indah, Penuh Berkah Bersama Al-Qur'an",
            'about'=>'Tentang kami',
        ];
        foreach($homepage as $value){
            Setting::firstOrCreate([
                'group'=>'homepage',
                'name'=>$value,
            ],[
                'payload'=>'',
            ]);
        }
    }
}
