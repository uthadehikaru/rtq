<?php

namespace Database\Seeders;

use BinshopsBlog\Models\BinshopsConfiguration;
use BinshopsBlog\Models\BinshopsLanguage;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BinshopsConfiguration::firstOrCreate([
            'key' => 'INITIAL_SETUP',
        ], [
            'value' => 1,
        ]);

        BinshopsConfiguration::firstOrCreate([
            'key' => 'DEFAULT_LANGUAGE_LOCALE',
        ], [
            'value' => 'id',
        ]);

        BinshopsLanguage::firstOrCreate([
            'locale' => 'id',
        ], [
            'name' => 'Indonesian',
            'iso_code' => 'id',
            'date_format' => 'DD/MMM/YYYY',
            'active' => 1,
            'rtl' => 0,
        ]);
    }
}
