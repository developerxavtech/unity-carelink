<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Conversation;
use App\Models\Organization;
use Spatie\Permission\Models\Role;
use App\Models\Team;
use App\Models\IndividualProfile;

class ChatTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
    }

    public function test_family_can_view_chat_index()
    {
        $user = User::factory()->create();
        $user->assignRole('family_admin');

        $response = $this->actingAs($user)
            ->get(route('chat.index'));

        $response->assertStatus(200);
    }

    public function test_family_can_create_conversation()
    {
        $family = User::factory()->create();
        $family->assignRole('family_admin');

        $dsp = User::factory()->create();
        $dsp->assignRole('dsp');

        $response = $this->actingAs($family)
            ->post(route('chat.store'), [
                'user_id' => $dsp->id,
                'subject' => 'Test Subject',
                'message' => 'Hello DSP!',
            ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('conversations', ['subject' => 'Test Subject']);
        $this->assertDatabaseHas('messages', ['content' => 'Hello DSP!']);
    }

    public function test_dsp_can_send_message()
    {
        $user = User::factory()->create();
        $user->assignRole('dsp');

        $conversation = Conversation::create(['subject' => 'Existing Chat']);
        $conversation->addParticipant($user->id);

        $response = $this->actingAs($user)
            ->post(route('chat.messages.send', $conversation), [
                'content' => 'Reply from DSP',
            ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'content' => 'Reply from DSP',
            'user_id' => $user->id,
        ]);
    }

    public function test_program_staff_can_view_any_chat()
    {
        $staff = User::factory()->create();
        $staff->assignRole('program_staff');

        $otherUser = User::factory()->create();
        $conversation = Conversation::create(['subject' => 'Private Chat']);
        $conversation->addParticipant($otherUser->id);

        $response = $this->actingAs($staff)
            ->get(route('chat.show', $conversation));

        $response->assertStatus(200);
        $response->assertSee('Private Chat');
    }

    public function test_family_admin_can_see_same_family_in_potential_participants()
    {
        $family1 = User::factory()->create();
        $family1->assignRole('family_admin');

        $family2 = User::factory()->create();
        $family2->assignRole('family_admin');

        $conversation = Conversation::create(['subject' => 'Family Chat']);
        $conversation->addParticipant($family1->id);

        $response = $this->actingAs($family1)
            ->get(route('chat.show', $conversation));

        $response->assertStatus(200);
        $response->assertViewHas('potentialParticipants', function ($users) use ($family2) {
            return $users->contains($family2);
        });
    }

    public function test_family_admin_can_see_assigned_dsp_in_potential_participants()
    {
        $family = User::factory()->create();
        $family->assignRole('family_admin');

        $dsp = User::factory()->create();
        $dsp->assignRole('dsp');

        $conversation = Conversation::create(['subject' => 'Chat with DSP']);
        $conversation->addParticipant($family->id);

        $response = $this->actingAs($family)
            ->get(route('chat.show', $conversation));

        $response->assertStatus(200);
        $response->assertViewHas('potentialParticipants', function ($users) use ($dsp) {
            return $users->contains($dsp);
        });
    }

    public function test_dsp_can_see_supported_family_in_potential_participants()
    {
        $dsp = User::factory()->create();
        $dsp->assignRole('dsp');

        $family = User::factory()->create();
        $family->assignRole('family_admin');

        $conversation = Conversation::create(['subject' => 'Chat']);
        $conversation->addParticipant($dsp->id);

        $response = $this->actingAs($dsp)
            ->get(route('chat.show', $conversation));

        $response->assertStatus(200);
        $response->assertViewHas('potentialParticipants', function ($users) use ($family) {
            return $users->contains($family);
        });
    }

    public function test_dsp_can_see_other_dsp_in_potential_participants()
    {
        $dsp1 = User::factory()->create();
        $dsp1->assignRole('dsp');

        $dsp2 = User::factory()->create();
        $dsp2->assignRole('dsp');

        $conversation = Conversation::create(['subject' => 'DSP Chat']);
        $conversation->addParticipant($dsp1->id);

        $response = $this->actingAs($dsp1)
            ->get(route('chat.show', $conversation));

        $response->assertStatus(200);
        $response->assertViewHas('potentialParticipants', function ($users) use ($dsp2) {
            return $users->contains($dsp2);
        });
    }
}
