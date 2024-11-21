<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $programs = ['Wakaf Bangunan', 'Donasi Palestina', 'Kencleng BIM'];
        foreach ($programs as $program) {
            Program::factory()->create(['title' => $program]);
        }
    }
}
