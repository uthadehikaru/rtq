<?php

namespace Tests\Feature\Admin;

use App\Models\Teacher;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\UserSeeder;

uses()->group('admin');

beforeEach(function () {
    $this->seed([
        PermissionSeeder::class,
        UserSeeder::class,
    ]);
});

it('admin can see teacher', function () {
    $admin = User::find(1);
    actingAs($admin);
    $response = $this->get(route('teachers.index'));
    $response->assertStatus(200);
});

it('admin can insert teacher', function () {
    $admin = User::find(1);
    actingAs($admin);

    foreach (Teacher::STATUSES as $status) {
        $data = [
            'name' => $status,
            'email' => $status.'@gmail.com',
            'batch_ids' => '',
            'status' => $status,
        ];

        $response = $this->post(route('teachers.store'), $data);
        $response->assertStatus(302)->assertSessionHas('message');
        $response->assertRedirect(route('teachers.index'));
    }
});

it('admin can update teacher', function () {
    $admin = User::find(1);
    $teacher = Teacher::factory()->create();
    actingAs($admin);
    $data = [
        'name' => $teacher.' 1',
        'email' => 'test@gmail.com',
        'batch_ids' => null,
        'status' => 'khidmat',
    ];

    $response = $this->put(route('teachers.update', $teacher->id), $data);
    $response->assertStatus(302)->assertSessionHas('message');
    $response->assertRedirect(route('teachers.index'));
});

it('admin can delete teacher', function () {
    $admin = User::find(1);
    $teacher = Teacher::factory()->for(User::factory()->create())->create();
    actingAs($admin);
    $response = $this->delete(route('teachers.destroy', $teacher->id));
    $response->assertSuccessful();
});
