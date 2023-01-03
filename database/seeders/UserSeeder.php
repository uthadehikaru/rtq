<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = \App\Models\User::firstOrCreate([
            'name' => 'Admin',
            'email' => 'admin@rtqmaisuro.id',
        ], [
            'email_verified_at' => now(),
            'password' => Hash::make('bismillah'),
            'remember_token' => Str::random(10),
        ]);

        $user->assignRole('administrator');
    }
}
