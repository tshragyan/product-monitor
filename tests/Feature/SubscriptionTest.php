<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_without_body(): void
    {
        $response = $this->post('/api/subscribe');

        $response->assertStatus(302);

    }

    public function test_with_body_wrong_url()
    {
        $response = $this->post('/api/subscribe', ['email' => 'test@gmail.com', 'url' => 'https://amazon.com']);

        $response->assertStatus(400);
        $response->assertJson(['message' => 'We sent activation email to your address, please check your email for verifying']);
    }
}
