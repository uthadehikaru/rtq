<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Str;
use Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@rtqmaisuro.id',
            'email_verified_at' => now(),
            'password' => Hash::make('bismillah'),
            'remember_token' => Str::random(10),
            'type'=> 'admin',
        ]);
        
        $this->call([
            //CourseSeeder::class,
            //MemberSeeder::class,
            PeriodSeeder::class,
            //PaymentSeeder::class,
        ]);
    }
}
