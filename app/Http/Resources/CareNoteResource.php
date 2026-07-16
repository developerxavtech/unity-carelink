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
            'individual_name' => $this->whenLoaded('individualProfile', fn () => $this->individualProfile?->full_name),
            'family_user_id' => $this->family_user_id,
            'family_name' => $this->whenLoaded('familyAdmin', fn () => $this->familyAdmin ? trim($this->familyAdmin->first_name.' '.$this->familyAdmin->last_name) : null),
            'dsp_user_id' => $this->dsp_user_id,
            'dsp_name' => $this->whenLoaded('dsp', fn () => $this->dsp ? trim($this->dsp->first_name.' '.$this->dsp->last_name) : null),
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
