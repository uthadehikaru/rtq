<?php

namespace Tests\Feature;

use App\Models\Schedule;
use App\Services\SettingService;
use Carbon\Carbon;
use Database\Seeders\SettingSeeder;
use Illuminate\Http\UploadedFile;

uses()->group('teacher');

beforeEach(function () {
    $this->seed([
        SettingSeeder::class,
    ]);
});

test('teacher can access dashboard', function () {
    $user = createUser('teacher');
    $batch = createBatch($user);
    actingAs($user)
        ->get('dashboard')
        ->assertStatus(200)
        ->assertSee($user->name);
});

test('teacher can create their schedule', function () {
    $user = createUser('teacher');
    $batch = createBatch($user);

    Storage:fake('profiles');
    $file = UploadedFile::fake()->image('profile.jpg');

    $data = [
        'batch_id' => $batch->id,
        'scheduled_at' => Carbon::now()->addHour(),
        'teacher_id' => '',
        'badal' => 0,
        'photo' => $file,
        'lat' => 1,
        'long' => 1,
    ];
    actingAs($user)
        ->post(route('teacher.schedules.create'), $data)
        ->assertJson([
            'error' => null,
        ]);

    $schedule = Schedule::first();
    expect($schedule->batch_id)->toBe($batch->id);

    $data = [
        'photo' => $file,
        'lat' => 1,
        'long' => 1,
    ];
    actingAs($user)
        ->post(route('teacher.schedules.close', $schedule->id), $data)
        ->assertJson([
            'error' => null,
        ]);

    $schedule->refresh();
    expect($schedule->end_at)->not()->toBeEmpty();
});

test('teacher cannot create past schedule', function () {
    $user = createUser('teacher');
    $batch = createBatch($user);

    Storage:fake('profiles');
    $file = UploadedFile::fake()->image('profile.jpg');

    $data = [
        'batch_id' => $batch->id,
        'scheduled_at' => Carbon::now()->subHour(),
        'teacher_id' => '',
        'badal' => 0,
        'photo' => $file,
        'lat' => 1,
        'long' => 1,
    ];
    actingAs($user)
        ->post(route('teacher.schedules.create'), $data)
        ->assertJson([
            'error' => null,
        ]);
});

test('teacher can close schedule after min duration', function () {
    $user = createUser('teacher');
    $batch = createBatch($user);

    $service = new SettingService();
    $service->set('durasi_'.str($batch->course->type)->snake(), 120);

    Storage:fake('profiles');
    $file = UploadedFile::fake()->image('profile.jpg');

    $data = [
        'batch_id' => $batch->id,
        'scheduled_at' => Carbon::now()->addHour(),
        'teacher_id' => '',
        'badal' => 0,
        'photo' => $file,
        'lat' => 1,
        'long' => 1,
    ];
    actingAs($user)
        ->post(route('teacher.schedules.create'), $data)
        ->assertJson([
            'error' => null,
        ]);

    $schedule = Schedule::first();
    expect($schedule->batch_id)->toBe($batch->id);

    $this->travel(3)->hours();

    $data = [
        'photo' => $file,
        'lat' => 1,
        'long' => 1,
    ];
    actingAs($user)
        ->post(route('teacher.schedules.close', $schedule->id), $data)
        ->assertJson([
            'error' => null,
        ]);

    $schedule->refresh();
    expect($schedule->end_at)->not()->toBeEmpty();
});

test('teacher can not close schedule before min duration', function () {
    $user = createUser('teacher');
    $batch = createBatch($user);

    $service = new SettingService();
    $service->set('durasi_'.str($batch->course->type)->snake(), 120);

    Storage:fake('profiles');
    $file = UploadedFile::fake()->image('profile.jpg');

    $data = [
        'batch_id' => $batch->id,
        'scheduled_at' => Carbon::now()->addHour(),
        'teacher_id' => '',
        'badal' => 0,
        'photo' => $file,
        'lat' => 1,
        'long' => 1,
    ];
    actingAs($user)
        ->post(route('teacher.schedules.create'), $data)
        ->assertJson([
            'error' => null,
        ]);

    $schedule = Schedule::first();
    expect($schedule->batch_id)->toBe($batch->id);

    $data = [
        'photo' => $file,
        'lat' => 1,
        'long' => 1,
    ];
    actingAs($user)
        ->post(route('teacher.schedules.close', $schedule->id), $data)
        ->assertJson([
            'error' => 'Belum diperbolehkan absen keluar, minimal jam '.Carbon::now()->addMinutes(120)->format('H:i'),
        ]);
});
