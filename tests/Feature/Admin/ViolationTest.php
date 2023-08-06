<?php

namespace Tests\Feature\Admin;

use App\Models\Batch;
use App\Models\Course;
use App\Models\Member;
use App\Models\User;
use App\Models\Violation;
use Carbon\Carbon;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Role;

uses()->group('admin');

beforeEach(function () {
    $this->seed([
        PermissionSeeder::class,
        UserSeeder::class,
    ]);
});

test('admin can see violation', function () {
    $admin = User::find(1);
    actingAs($admin);
    $response = $this->get(route('violations.index'));
    $response->assertStatus(200);
});

test('non admin cannot see violation', function () {
    $roles = Role::where('name','<>','administrator')->get();
    foreach($roles as $role){
        $user = createUser($role->name);
        actingAs($user);
        $response = $this->get(route('violations.index'));
        $response->assertStatus(403);
    }
});

test('admin can access create violation', function () {
    $admin = User::find(1);
    actingAs($admin);
    $response = $this->get(route('violations.create'));
    $response->assertStatus(200);
});

test('admin can insert violation', function () {
    $admin = User::find(1);
    actingAs($admin);
    $violation = Violation::factory()->make()->toArray();
    $response = $this->post(route('violations.store'), $violation);
    $response->assertSessionHasNoErrors();
    expect(Violation::count())->toBe(1);
});

test('admin can access edit violation', function () {
    $admin = User::find(1);
    actingAs($admin);
    $violation = Violation::factory()->create();
    $response = $this->get(route('violations.edit', $violation->id));
    $response->assertStatus(200);
});

test('admin can update violation', function () {
    $admin = User::find(1);
    actingAs($admin);
    $violation = Violation::factory()->create()->toArray();
    $violation['paid_at'] = Carbon::now();
    $response = $this->put(route('violations.update', $violation['id']), $violation);
    $response->assertSessionHasNoErrors();
    $violation = Violation::find($violation['id']);
    expect($violation->paid_at)->not()->toBeNull();
});