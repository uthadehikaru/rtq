<?php

beforeEach(function () {
    config(['health.token' => null]);
});

it('returns ok when the application is healthy', function () {
    $response = $this->get('/health');

    $response
        ->assertOk()
        ->assertJson([
            'status' => 'ok',
            'checks' => [
                'database' => 'ok',
            ],
        ]);
});

it('returns ok when a valid health token is provided via header', function () {
    config(['health.token' => 'secret-token']);

    $response = $this->withHeader('X-Health-Token', 'secret-token')->get('/health');

    $response->assertOk()->assertJson(['status' => 'ok']);
});

it('returns ok when a valid health token is provided via query string', function () {
    config(['health.token' => 'secret-token']);

    $response = $this->get('/health?token=secret-token');

    $response->assertOk()->assertJson(['status' => 'ok']);
});

it('returns unauthorized when the health token is missing or invalid', function () {
    config(['health.token' => 'secret-token']);

    $this->get('/health')->assertUnauthorized()->assertJson([
        'status' => 'error',
        'message' => 'Unauthorized',
    ]);

    $this->withHeader('X-Health-Token', 'wrong-token')->get('/health')->assertUnauthorized();
});
