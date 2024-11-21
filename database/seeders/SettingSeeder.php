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
        $general = [
            'idcard' => '',
            'latitude' => '-6.2214295',
            'longitude' => '106.7737958',
        ];
        foreach ($general as $name => $value) {
            Setting::firstOrCreate([
                'group' => 'general',
                'name' => $name,
            ], [
                'payload' => $value,
            ]);
        }

        $settings = [
            'oper_santri',
            'transportasi',
            'tunjangan',
            'telat_tanpa_konfirmasi',
            'maks_telat_dengan_konfirmasi',
            'pengurangan_tunjangan_per_izin',
            'maks_izin',
            'maks_waktu_telat',
        ];

        foreach (Course::TYPES as $type) {
            $settings[] = Str::snake($type);
        }

        foreach ($settings as $setting) {
            Setting::firstOrCreate([
                'group' => 'salary',
                'name' => $setting,
            ], [
                'payload' => 0,
            ]);
        }

        $courses = [
            'course_fee' => 120000,
        ];
        foreach (Course::TYPES as $type) {
            $courses['durasi_'.Str::snake($type)] = 0;
        }
        $courses['acceleration_tahsin_anak_fee'] = 220000;
        $courses['acceleration_tahsin_dewasa_fee'] = 200000;

        foreach ($courses as $name => $value) {
            Setting::firstOrCreate([
                'group' => 'course',
                'name' => $name,
            ], [
                'payload' => $value,
            ]);
        }

        $homepage = [
            'banner' => '',
            'tagline' => "Hidup Indah, Penuh Berkah Bersama Al-Qur'an",
            'about' => 'Tentang kami',
            'instagram' => 'https://instagram.com/rumahtartilquran_mahmud',
            'whatsapp' => '',
        ];
        foreach ($homepage as $name => $value) {
            Setting::firstOrCreate([
                'group' => 'homepage',
                'name' => $name,
            ], [
                'payload' => $value,
            ]);
        }
    }
}
