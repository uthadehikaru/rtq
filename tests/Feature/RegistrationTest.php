<?php

namespace Tests\Feature;

use App\Models\Registration;
use Carbon\Carbon;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\UserSeeder;

test('guest can access register based on type', function () {
    $types = ['dewasa', 'anak', 'balita'];
    foreach ($types as $type) {
        $response = $this->get(route('register', $type));
        $response->assertStatus(200);
    }
});

test('guest cannot access register without type', function () {
    $response = $this->get('register');
    $response->assertStatus(404);
});

test('guest can register for every type', function () {
    $this->seed([PermissionSeeder::class, UserSeeder::class]);
    $types = ['dewasa', 'anak', 'balita'];
    foreach ($types as $type) {
        $data = Registration::factory()->make()->toArray();
        $nik = Carbon::parse($data['birth_date'])->format('dmy');
        if ($data['gender'] == 'female') {
            $nik += 400000;
        }
        $data['nik'] = str_replace(substr($data['nik'], 6, 6), $nik, $data['nik']);
        $response = $this->post(route('register', $type), $data);
        $response->assertStatus(200);
    }
});

test('guest cannot register with different nik and birth_date', function () {
    $data = Registration::factory()->make()->toArray();
    $response = $this->post(route('register', 'anak'), $data);
    $response->assertSessionHas('errors');
});

test('guest cannot register with same nik and birth_date but different gender', function () {
    $data = Registration::factory()->make()->toArray();
    $nik = Carbon::parse($data['birth_date'])->format('dmy');
    $data['gender'] = 'female';
    $data['nik'] = str_replace(substr($data['nik'], 6, 6), $nik, $data['nik']);
    $response = $this->post(route('register', 'anak'), $data);
    $response->assertSessionHas('errors');
});
