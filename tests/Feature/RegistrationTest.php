<?php

namespace Tests\Feature;

test('guest can access register based on type', function () {
    $types = ['dewasa', 'anak', 'balita'];
    foreach ($types as $type) {
        $response = $this->get(route('register', $type));
        $response->assertStatus(200);
    }
});

test('guest cannot access register without type', function () {
    $response = $this->get('register');
    $response->assertStatus(404);
});
