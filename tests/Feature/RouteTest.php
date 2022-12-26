<?php

namespace Tests\Feature;

it('can access homepage', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

it('can access payment confirm', function () {
    $response = $this->get(route('payment'));

    $response->assertStatus(200);
});
