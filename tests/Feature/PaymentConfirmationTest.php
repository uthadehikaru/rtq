<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Period;
use App\Models\User;
use Database\Seeders\PeriodSeeder;

test('guest can access payment confirmation', function () {
    $response = $this->get(route('payment'));
    $response->assertStatus(200);
});

test('guest can create payment confirmation', function () {
    $this->seed(PeriodSeeder::class);
    $period = Period::first();
    $member = Member::factory()->for(User::factory())->create(['birth_date' => '2020-02-25', 'gender' => 'male']);
    $data = [
        'period_ids' => [$period->id],
        'members' => [$member->id],
        'total' => 120000,
    ];
    $response = $this->post(route('payment.confirm'), $data);
    $response->assertSessionHas('message');
});

test('guest cannot create payment confirmation without valid data', function () {
    $this->seed(PeriodSeeder::class);
    $period = Period::first();
    $member = Member::factory()->for(User::factory())->create(['birth_date' => '2020-02-25', 'gender' => 'male']);
    $data = [
        'period_ids' => [$period->id],
        'members' => [$member->id],
        'total' => 0,
    ];
    $response = $this->post(route('payment.confirm'), $data);
    $response->assertSessionMissing('message');
});
