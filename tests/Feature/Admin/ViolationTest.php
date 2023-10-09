<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Violation;
use App\Notifications\UserIqobCreated;
use Carbon\Carbon;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;

uses()->group('admin');

beforeEach(function () {
    Notification::fake();

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

test('admin can insert violation with no user', function () {
    $admin = User::find(1);
    actingAs($admin);
    $violation = Violation::factory()->make()->toArray();
    $response = $this->post(route('violations.store'), $violation);
    $response->assertSessionHasNoErrors();

    Notification::assertNothingSent();
    expect(Violation::count())->toBe(1);
});

test('admin can insert violation for member', function () {
    $admin = User::find(1);
    actingAs($admin);
    $user = createUser('member');
    $violation = Violation::factory()->make()->toArray();
    $violation['user_id'] = $user->id;
    $violation['description'] = "Telat Masuk";
    $violation['amount'] = 500;
    $response = $this->post(route('violations.store'), $violation);
    $response->assertSessionHasNoErrors();

    Notification::assertSentTo(
        [$user],
        function (UserIqobCreated $notification, array $channels) use ($user) {
            return $notification->toArray($user)['title'] === 'Iqob : Telat Masuk Rp. 500';
        }
    );
    expect(Violation::count())->toBe(1);
});

test('admin can insert violation for teacher', function () {
    $admin = User::find(1);
    actingAs($admin);
    $user = createUser('teacher');
    $violation = Violation::factory()->make()->toArray();
    $violation['user_id'] = $user->id;
    $violation['type'] = 'teacher';
    $violation['description'] = "Lupa tutup kelas";
    $violation['amount'] = 1000;
    $response = $this->post(route('violations.store'), $violation);
    $response->assertSessionHasNoErrors();

    Notification::assertSentTo(
        [$user],
        function (UserIqobCreated $notification, array $channels) use ($user) {
            return $notification->toArray($user)['title'] === 'Iqob : Lupa tutup kelas Rp. 1.000';
        }
    );
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

test('non admin can see their own violation', function () {
    $roles = Role::where('name','<>','administrator')->get();
    foreach($roles as $role){
        $user = createUser($role->name);
        actingAs($user);
        $response = $this->get(route('iqob.index'));
        $response->assertStatus(200);
    }
});