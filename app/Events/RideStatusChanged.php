<?php

namespace App\Events;

use App\Models\Ride;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Broadcast on the ride's own channel for start/complete/cancel — both
 * parties already know the ride id by this point (unlike the initial
 * request), so this uses a shared per-ride channel rather than per-user.
 */
class RideStatusChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(public Ride $ride)
    {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('ride.'.$this->ride->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'ride.status.changed';
    }

    public function broadcastWith(): array
    {
        return [
            'ride_id' => $this->ride->id,
            'status' => $this->ride->status,
            'started_at' => $this->ride->started_at?->toIso8601String(),
            'ended_at' => $this->ride->ended_at?->toIso8601String(),
        ];
    }
}
