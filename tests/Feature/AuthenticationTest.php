<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_correct_credentials(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/v1/auth/login', [
            'email'=> $user->email,
            'password'=> 'password',
        ]);

        $response->assertStatus(201);
    }

    public function test_user_cannot_login_with_incorrect_credentials(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/v1/auth/login', [
            'email'=> $user->email,
            'password'=> 'wrong_password',
        ]);

        $response->assertStatus(422);
    }

    public function test_user_can_register_with_correct_credentials(): void
    {
        // $user = User::factory()->create(); no arrange here

        $response = $this->postJson('/api/v1/auth/register', [
            'name'                  => 'mohannad',
            'email'                 => 'mohannad@test.com',
            'password'              => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201)
        ->assertJsonStructure([
            'access_token'
        ]);
        $this->assertDatabaseHas('users', [
            'name'=> 'mohannad',
            'email'=> 'mohannad@test.com',
        ]);
    }

    public function test_user_cannot_register_with_incorrect_credentials(): void
    {
        // $user = User::factory()->create(); no arrange here

        $response = $this->postJson('/api/v1/auth/register', [
            'name'                  => 'mohannad',
            'email'                 => 'mohannad@test.com',
            'password'              => 'password',
            'password_confirmation' => 'wrong_password',
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseMissing('users', [
            'name'=> 'mohannad',
            'email'=> 'mohannad@test.com',
        ]);
    }
}
