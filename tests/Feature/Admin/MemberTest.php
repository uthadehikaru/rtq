<?php

namespace Tests\Feature\Admin;

use App\Models\Batch;
use App\Models\Course;
use App\Models\Member;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\UserSeeder;

uses()->group('admin');

beforeEach(function () {
    $this->seed([
        PermissionSeeder::class,
        UserSeeder::class,
    ]);

    Member::factory(10)->has(User::factory());
});

it('admin can see member', function () {
    $admin = User::find(1);
    actingAs($admin);
    $response = $this->get(route('members.index'));
    $response->assertViewHas('members');
    $response->assertStatus(200);
});

it('admin can create member', function () {
    $admin = User::find(1);
    $batches = Batch::factory(2)->has(Course::factory());
    actingAs($admin);
    $data = [
        'batch_id' => $batches->pluck('id'),
        'full_name' => 'MEmber 1',
        'short_name' => 'member',
        'email' => 'member@gmail.com',
        'phone' => '0812341234',
        'gender' => 'male',
        'school' => 'SD',
        'class' => '1',
        'level' => 'iqro',
        'address' => 'alamat',
        'postcode' => '12740',
    ];

    $response = $this->post(route('members.store'), $data);
    $response->assertRedirect(route('members.index'));
});
