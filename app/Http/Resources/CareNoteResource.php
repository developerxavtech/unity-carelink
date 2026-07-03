<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CareNoteResource extends JsonResource
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
            'individual_profile_id' => $this->individual_profile_id,
            'individual_name' => $this->whenLoaded('individualProfile', fn () => $this->individualProfile->full_name),
            'dsp_user_id' => $this->dsp_user_id,
            'shift_date' => $this->shift_date?->toDateString(),
            'notes' => $this->notes,
            'mood' => $this->mood,
            'activities' => $this->activities,
            'meals' => $this->meals,
            'medications' => $this->medications,
            'incidents' => $this->incidents,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
