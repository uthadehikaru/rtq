<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;

test('user can access login', function () {
    $response = $this->get(route('login'));
    $response->assertStatus(200);
});

test('user can login using email', function () {
    $this->seed(DatabaseSeeder::class);
    $credentials = [
        'email' => 'admin@rtqmaisuro.id',
        'password' => 'bismillah',
    ];
    $response = $this->post(route('login'), $credentials);
    $this->assertAuthenticated();
});

test('user can login using username', function () {
    $this->seed(DatabaseSeeder::class);
    $credentials = [
        'email' => 'admin',
        'password' => 'bismillah',
    ];
    $response = $this->post(route('login'), $credentials);
    $this->assertAuthenticated();
});

test('user cannot login using wrong password', function () {
    $this->seed(DatabaseSeeder::class);
    $credentials = [
        'email' => 'admin',
        'password' => 'wrongpassword',
    ];
    $response = $this->post(route('login'), $credentials);
    $this->assertGuest();
});
