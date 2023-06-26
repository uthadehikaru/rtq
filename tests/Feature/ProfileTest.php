<?php

use App\Http\Livewire\Profile;
use App\Models\User;
use Livewire\Livewire;

test('guest cannot access profile', function () {
    $this->get('profile')
    ->assertRedirectToRoute('login');
});

test('user can access profile', function () {
    $user = User::factory()->create();
    actingAs($user)->get('profile')
    ->assertStatus(200)
    ->assertSeeInOrder([$user->name,$user->email]);
});

test('user can update profile', function () {
    $user = User::factory()->create();
    actingAs($user);
    Livewire::test(Profile::class)
    ->set('user.name','foo')
    ->set('user.email', 'foo@bar')
    ->set('user.is_notify', 0)
    ->call('update')
    ->assertSee('Profile berhasil diperbaharui');
});