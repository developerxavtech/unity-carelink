<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->role,
            'status' => $this->status,
            'profile_photo' => $this->profile_photo,
            'mood' => [
                'activity_status' => $this->activity_status,
                'status_emoji' => $this->status_emoji,
                'status_message' => $this->status_message,
                'status_busy_until' => $this->status_busy_until
                    ? $this->status_busy_until->toIso8601String()
                    : null,
                'full_status' => $this->full_status,
                'is_busy' => $this->isBusy(),
            ],
        ];
    }
}
