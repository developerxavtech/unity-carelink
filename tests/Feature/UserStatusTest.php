<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_access_status_edit_page()
    {
        $user = \App\Models\User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('family.status.edit'));

        $response->assertStatus(200);
        $response->assertSee('Update My Status');
        $response->assertSee('Busy at the mall');
    }

    public function test_user_can_update_status()
    {
        $user = \App\Models\User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('family.status.update'), [
                'activity_status' => 'Swimming',
                'status_emoji' => '🏊',
                'status_message' => 'Having a great time!',
                'status_busy_until' => now()->addHour()->toDateTimeString(),
            ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'activity_status' => 'Swimming',
            'status_emoji' => '🏊',
            'status_message' => 'Having a great time!',
        ]);

        $user->refresh();
        $this->assertTrue($user->isBusy());
        $this->assertEquals('🏊 Swimming', $user->full_status);
    }

    public function test_user_can_clear_status()
    {
        $user = \App\Models\User::factory()->create([
            'activity_status' => 'Busy',
            'status_emoji' => '🚫',
        ]);

        $response = $this->actingAs($user)
            ->post(route('family.status.clear'));

        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'activity_status' => null,
            'status_emoji' => null,
            'status_message' => null,
            'status_busy_until' => null,
        ]);

        $user->refresh();
        $this->assertFalse($user->isBusy());
        $this->assertEquals('Available', $user->full_status);
    }
}
