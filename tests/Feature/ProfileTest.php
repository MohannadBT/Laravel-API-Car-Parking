<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_get_their_profile(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('api/v1/profile');

        $response->assertStatus(200)
        ->assertJsonStructure(['name', 'email'])
        ->assertJsonCount(2)
        ->assertJsonFragment(['name' => $user->name]);
    }

    public function test_user_can_update_name_and_email(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->putJson('api/v1/profile',[
            'name'=> 'updated test',
            'email'=> 'updated@test.com',
        ]);

        $response->assertStatus(202)
        ->assertJsonStructure(['name', 'email'])
        ->assertJsonCount(2)
        ->assertJsonFragment(['name' => 'updated test']);

        $this->assertDatabaseHas('users', [
            'name'=> 'updated test',
            'email'=> 'updated@test.com',
        ]);
    }

    public function test_user_can_change_password(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->putJson('api/v1/password',[
            'current_password'=> 'password',
            'password'=> 'test_new_password',
            'password_confirmation'=> 'test_new_password',
        ]);

        $response->assertStatus(202);
    }
}