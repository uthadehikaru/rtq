<?php

namespace Tests\Feature;

use Carbon\Carbon;

uses()->group('teacher');

test('teacher can access dashboard', function () {
    $user = createTeacher();
    $batch = createBatch($user);
    actingAs($user)
    ->get('dashboard')
    ->assertStatus(200)
    ->assertSee($user->name)
    ->assertSee($batch->name);
});

test('teacher can create their schedule', function () {
    $user = createTeacher();
    $batch = createBatch($user);

    $data = [
        'batch_id' => $batch->id,
        'scheduled_at' => Carbon::now()->addHour(),
        'teacher_id' => '',
        'badal' => 0,
    ];
    actingAs($user)
    ->post(route('teacher.schedules.create'), $data)
    ->assertSessionHas('message');
});

test('teacher cannot create past schedule', function () {
    $user = createTeacher();
    $batch = createBatch($user);

    $data = [
        'batch_id' => $batch->id,
        'scheduled_at' => Carbon::now()->subHour(),
        'teacher_id' => '',
    ];
    actingAs($user)
    ->post(route('teacher.schedules.create'), $data)
    ->assertSessionHas('errors');
});
