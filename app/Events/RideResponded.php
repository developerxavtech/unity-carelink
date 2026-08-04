<?php

namespace App\Events;

use App\Models\Ride;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Broadcast to the family admin's personal channel when the DSP accepts or
 * rejects their ride request.
 */
class RideResponded implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(public Ride $ride)
    {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.'.$this->ride->family_user_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'ride.responded';
    }

    public function broadcastWith(): array
    {
        return [
            'ride_id' => $this->ride->id,
            'status' => $this->ride->status,
            'rejection_reason' => $this->ride->rejection_reason,
        ];
    }
}
