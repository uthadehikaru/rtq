<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RouteTest extends TestCase
{
    /**
     * Testing Homepage
     *
     * @return void
     */
    public function test_homepage()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Testing Konfirmasi Pembayaran
     *
     * @return void
     */
    public function test_payment_confirm()
    {
        $response = $this->get(route('payment'));

        $response->assertStatus(200);
    }
}
