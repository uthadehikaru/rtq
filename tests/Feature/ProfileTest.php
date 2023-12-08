<?php

use App\Livewire\Profile;
use App\Models\User;
use Livewire\Livewire;

test('guest cannot access profile', function () {
    $this->get('profile')
    ->assertRedirectToRoute('login');
});

test('user can access profile', function () {
    $user = User::factory()->create();
    Livewire::actingAs($user)
    ->test(Profile::class)
    ->assertStatus(200)
    ->assertSee($user->name)
    ->assertSee($user->email);
});

test('user can update profile', function () {
    $user = User::factory()->create();
    Livewire::actingAs($user)
    ->test(Profile::class)
    ->call('update')
    ->assertSee('Profile berhasil diperbaharui');
});