<?php

namespace Tests\Feature;

use App\Models\Ride;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RideApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'family_admin', 'guard_name' => 'web']);
        Role::create(['name' => 'dsp', 'guard_name' => 'web']);
    }

    public function test_show_ride_returns_pickup_and_destination_lat_lng(): void
    {
        $family = User::factory()->create();
        $family->assignRole('family_admin');

        $dsp = User::factory()->create();
        $dsp->assignRole('dsp');

        $ride = Ride::create([
            'family_user_id' => $family->id,
            'dsp_user_id' => $dsp->id,
            'vehicle_type' => 'sedan',
            'status' => Ride::STATUS_PENDING,
            'pickup_address' => '123 Main St, Columbus, OH',
            'pickup_latitude' => 39.9612,
            'pickup_longitude' => -82.9988,
            'destination_address' => '456 Broad St, Columbus, OH',
            'destination_latitude' => 40.1234,
            'destination_longitude' => -83.5678,
            'distance_miles' => 3.5,
            'fare' => 15.00,
        ]);

        \Laravel\Passport\Passport::actingAs($family);

        $response = $this
            ->getJson("/api/rides/{$ride->id}");

        $response->assertOk()
            ->assertJsonPath('data.id', $ride->id)
            ->assertJsonPath('data.pickup_address', '123 Main St, Columbus, OH')
            ->assertJsonPath('data.pickup_latitude', 39.9612)
            ->assertJsonPath('data.pickup_longitude', -82.9988)
            ->assertJsonPath('data.pickup_lat', 39.9612)
            ->assertJsonPath('data.pickup_lng', -82.9988)
            ->assertJsonPath('data.destination_address', '456 Broad St, Columbus, OH')
            ->assertJsonPath('data.destination_latitude', 40.1234)
            ->assertJsonPath('data.destination_longitude', -83.5678)
            ->assertJsonPath('data.destination_lat', 40.1234)
            ->assertJsonPath('data.destination_lng', -83.5678);
    }
}
