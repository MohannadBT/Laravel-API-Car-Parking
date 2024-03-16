<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VehicleTest extends TestCase
{
    use RefreshDatabase;
    public function test_user_can_get_their_own_vehicle(): void
    {
        $ali = User::factory()->create();
        $vehicleForAli = Vehicle::factory()->create([
            "user_id"=> $ali->id,
        ]);

        $hadi = User::factory()->create();
        $vehicleForHadi = Vehicle::factory()->create([
            "user_id"=> $hadi->id,
        ]);

        $response = $this->actingAs($ali)->getJson('/api/v1/vehicles');

        $response->assertStatus(200)
        ->assertJsonStructure(['data'])
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.plate_number', $vehicleForAli->plate_number)
        ->assertJsonMissing($vehicleForHadi->toArray());
    }

    public function test_user_can_create_vehicle()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/v1/vehicles', [
            'plate_number' => 'TST001'
        ]);

        $response->assertStatus(201)
        ->assertJsonStructure(['data'])
        ->assertJsonCount(2, 'date')
        ->assertJsonStructure([
            'data' => ['0' => 'plate_number'],
        ])
        ->assertJsonPath('data.0.plate_number', 'TST001');

        $this->assertDataBaseHas('vehicles', [
            'plate_number' => 'TST001'
        ]);
    }

    public function test_user_can_update_their_vehicle()
    {
        $user = User::factory()->create();
        $vehicle = Vehicle::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->putJson('/api/v1/vehicles' . $vehicle->id, [
            'plate_number' => 'UPD999',
        ]);

        $response->assertStatus(202)
        ->assertJsonStructure(['plate_number'])
        ->assertJsonPath('plate_number','UPD999');

        $this->assertDatabaseHas('vehicles', [
            'plate_number' => 'UPD999',
        ]);
    }

    public function test_user_can_delete_their_vehicle()
    {
        $user = User::factory()->create();
        $vehicle = Vehicle::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->deleteJson('/api/v1/vehicles' . $vehicle->id);

        $response->assertNoContent();

        $this->assertDatabaseMissing('vehicles', [
            'id' => $vehicle->id,
            'deleted_at' => null
        ])->assertDatabaseCount('vehicles', 1);
    }
}