<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\SettingSeeder;
use Database\Seeders\UserSeeder;
use Spatie\Permission\Models\Role;

uses()->group('admin');

beforeEach(function () {
    $this->seed([
        SettingSeeder::class,
        PermissionSeeder::class,
        UserSeeder::class,
    ]);
});

test('admin can see settings', function () {
    $admin = User::find(1);
    actingAs($admin);
    $response = $this->get(route('settings.index'));
    $response->assertStatus(200)
        ->assertSeeText('Halaman Depan')
        ->assertSeeText('Umum')
        ->assertSeeText('Kelas');
});

test('non admin cant see settings', function () {
    $roles = Role::where('name', '<>', 'administrator')->get();
    foreach ($roles as $role) {
        $user = createUser($role->name);
        actingAs($user);
        $response = $this->get(route('settings.index'));
        $response->assertStatus(403);
    }
});
