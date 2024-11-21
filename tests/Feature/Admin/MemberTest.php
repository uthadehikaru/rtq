<?php

namespace Tests\Feature\Admin;

use App\Models\Batch;
use App\Models\Course;
use App\Models\Member;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\Artisan;

uses()->group('admin');

beforeEach(function () {
    $this->seed([
        PermissionSeeder::class,
        UserSeeder::class,
    ]);
});

it('admin can see member', function () {
    $admin = User::find(1);
    actingAs($admin);
    $response = $this->get(route('members.index'));
    $response->assertStatus(200);
});

it('admin can create member', function () {
    $admin = User::find(1);
    $batch = Batch::factory()->for(Course::factory())->create();
    actingAs($admin);
    $data = [
        'batch_id' => $batch->id,
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
        'nik' => '1234123412341234',
        'birth_date' => '2015-12-01',
        'is_acceleration' => false,
    ];

    $response = $this->post(route('members.store'), $data);
    $response->assertStatus(302)->assertSessionHas('message');
    $response->assertRedirect(route('members.index'));
});

it('generate non duplicate member no', function () {
    Member::factory(2)->for(User::factory())->create(['birth_date' => '2020-02-25', 'gender' => 'male']);
    Member::factory(2)->for(User::factory())->create(['birth_date' => '2015-02-26', 'gender' => 'female']);

    Artisan::call('member:generateno', ['--reset' => 1]);

    Member::factory()->for(User::factory())->create(['birth_date' => '2015-02-26', 'gender' => 'female']);

    Artisan::call('member:generateno');

    $members = Member::whereNotNull('member_no')->select('member_no', 'full_name')
        ->orderBy('birth_date')
        ->orderBy('member_no')
        ->get();
    expect($members->count())->toBe(5);

    $memberno = '';
    foreach ($members as $member) {
        $same = $member->member_no == $memberno;
        expect($same)->toBeFalse();
        $memberno = $member->member_no;
    }
});
