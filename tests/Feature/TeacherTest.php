<?php

namespace Tests\Feature;

use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;

uses()->group('teacher');

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
