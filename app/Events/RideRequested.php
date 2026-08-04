<?php

namespace App\Events;

use App\Models\Ride;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Broadcast to the DSP's personal channel when a family admin books them —
 * lets a foregrounded DSP app show the incoming request live, in addition
 * to the FCM push sent separately for a backgrounded/killed app.
 */
class RideRequested implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(public Ride $ride)
    {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.'.$this->ride->dsp_user_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'ride.requested';
    }

    public function broadcastWith(): array
    {
        return [
            'ride_id' => $this->ride->id,
            'vehicle_type' => $this->ride->vehicle_type,
            'pickup_address' => $this->ride->pickup_address,
            'destination_address' => $this->ride->destination_address,
            'distance_miles' => (float) $this->ride->distance_miles,
            'fare' => (float) $this->ride->fare,
        ];
    }
}
