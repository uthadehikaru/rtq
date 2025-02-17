<?php

namespace Tests\Feature\Admin;

use App\Models\Batch;
use App\Models\Course;
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

test('admin can see batches', function () {
    $admin = User::find(1);
    actingAs($admin);
    $batch = Batch::factory()->for(Course::factory())->create();
    $response = $this->get(route('courses.batches.index', $batch->course_id));
    $response->assertStatus(200);
});

test('admin can create batch', function () {
    $admin = User::find(1);
    $course = Course::factory()->create();
    $teacher = Teacher::factory()->for(User::factory())->create();
    actingAs($admin);
    $data = [
        'course_id' => $course->id,
        'code' => 'test',
        'name' => 'Test',
        'description' => 'Desc',
        'start_time' => '07:00',
        'place' => 'somewhere',
        'teacher_ids' => [$teacher->id],
        'member_ids' => null,
        'is_active' => true,
    ];

    $response = $this->post(route('courses.batches.store', $course->id), $data);
    $response->assertStatus(302)->assertSessionHas('message');
});

test('admin can edit batch', function () {
    $admin = User::find(1);
    actingAs($admin);
    $batch = Batch::factory()->for(Course::factory())->create();
    $response = $this->get(route('courses.batches.edit', [$batch->course_id, $batch->id]));
    $response->assertViewHas('batch');
    $response->assertStatus(200);
});

test('khidmat teacher not appear on batch form', function () {
    $admin = User::find(1);
    $course = Course::factory()->create();
    $teacher = Teacher::factory()->for(User::factory())->create(['status' => 'khidmat']);
    actingAs($admin);
    $response = $this->get(route('courses.batches.create', $course->id));
    $response->assertSuccessful();
    $content = $response->getOriginalContent();
    $data = $content->getData();
    expect($data['teachers'])->not->toContain($teacher->name);
});
