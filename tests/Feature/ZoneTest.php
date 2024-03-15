<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ZoneTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_public_user_can_get_all_zones(): void
    {
        $response = $this->getJson('/api/v1/zones');

        $response->assertStatus(200)
        ->assertJsonStructure(['data'])
        ->assertJsonCount(3, 'data')
        ->assertJsonStructure(['data' => [
            '*' => 'id', 'name', 'price_per_hour'
        ]])
        ->assertJsonPath('data.0.id', 1)
        ->assertJsonPath('data.0.name', 'Green Zone')
        ->assertJsonPath('data.0.price_per_hour', 100);
    }
}
