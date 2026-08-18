<?php

namespace App\Events;

use App\Models\Conversation;
use App\Models\DspProfile;
use App\Models\Ride;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Broadcast live location updates of a DSP to all related family members
 * (families with active rides or assigned conversation clients) and on active
 * ride channels.
 */
class DspLocationUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(public User $dsp, public DspProfile $profile)
    {
    }

    public function broadcastOn(): array
    {
        $channels = [];

        // 1. Active rides with this DSP (pending, accepted, in_progress)
        $activeRides = Ride::where('dsp_user_id', $this->dsp->id)
            ->active()
            ->get();

        $familyUserIdsFromRides = $activeRides->pluck('family_user_id')->unique();

        foreach ($familyUserIdsFromRides as $familyUserId) {
            $channels[] = new PrivateChannel('user.'.$familyUserId);
        }

        foreach ($activeRides as $ride) {
            $channels[] = new PrivateChannel('ride.'.$ride->id);
        }

        // 2. Family admins involved in conversations with this DSP
        $conversationIds = Conversation::forUser($this->dsp->id)->pluck('id');

        $conversationFamilyUserIds = User::whereHas('roles', function ($q) {
            $q->where('name', 'family_admin');
        })
            ->whereHas('conversations', function ($q) use ($conversationIds) {
                $q->whereIn('conversations.id', $conversationIds);
            })
            ->where('id', '!=', $this->dsp->id)
            ->pluck('id');

        foreach ($conversationFamilyUserIds as $familyUserId) {
            if (! $familyUserIdsFromRides->contains($familyUserId)) {
                $channels[] = new PrivateChannel('user.'.$familyUserId);
            }
        }

        return $channels;
    }

    public function broadcastAs(): string
    {
        return 'dsp.location.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'dsp_id' => $this->dsp->id,
            'dsp_name' => trim($this->dsp->first_name.' '.$this->dsp->last_name),
            'latitude' => (float) $this->profile->latitude,
            'longitude' => (float) $this->profile->longitude,
            'is_available' => (bool) $this->profile->is_available,
            'location_updated_at' => $this->profile->location_updated_at?->toIso8601String(),
        ];
    }
}
