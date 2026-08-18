<?php

namespace Tests\Feature;

use App\Events\DspLocationUpdated;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Laravel\Passport\Passport;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DspLocationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'dsp', 'guard_name' => 'web']);
    }

    public function test_updating_dsp_location_dispatches_dsp_location_updated_event(): void
    {
        Event::fake([DspLocationUpdated::class]);

        $dsp = User::factory()->create();
        $dsp->assignRole('dsp');

        Passport::actingAs($dsp);

        $response = $this->postJson('/api/dsp/location', [
            'latitude' => 39.9612,
            'longitude' => -82.9988,
            'is_available' => true,
        ]);

        $response->assertOk()
            ->assertJsonPath('data.latitude', 39.9612)
            ->assertJsonPath('data.longitude', -82.9988)
            ->assertJsonPath('data.is_available', true);

        Event::assertDispatched(DspLocationUpdated::class, function ($event) use ($dsp) {
            return $event->dsp->id === $dsp->id
                && (float) $event->profile->latitude === 39.9612
                && (float) $event->profile->longitude === -82.9988;
        });
    }
}
