<?php

namespace Database\Seeders;

use Hash;
use Illuminate\Database\Seeder;
use Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
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
            'type' => 'admin',
        ]);
    }
}
