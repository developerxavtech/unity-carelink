<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DspClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * Represents a family admin (client) who is in a conversation
     * with the currently authenticated DSP.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Last message across all shared conversations
        $conversations   = $this->conversations ?? collect();
        $lastMessage     = null;
        $lastMessageTime = null;

        foreach ($conversations as $conv) {
            $msg = $conv->messages->first();
            if ($msg && (! $lastMessageTime || $msg->created_at > $lastMessageTime)) {
                $lastMessage     = $msg;
                $lastMessageTime = $msg->created_at;
            }
        }

        // Individual profiles managed by this family admin
        $individuals = $this->individualProfiles ?? collect();

        // Latest mood across all managed individuals
        $latestMood = null;
        foreach ($individuals as $individual) {
            $mood = ($individual->moodChecks ?? collect())->first();
            if ($mood) {
                $latestMood = $mood;
                break;
            }
        }

        // Latest care note shift date
        $latestCareNote = null;
        foreach ($individuals as $individual) {
            $note = ($individual->careNotes ?? collect())->first();
            if ($note) {
                $latestCareNote = $note;
                break;
            }
        }

        return [
            'id'           => $this->id,
            'full_name'    => $this->full_name,
            'email'        => $this->email,
            'phone'        => $this->phone,
            'profile_photo'=> $this->profile_photo,
            'status'       => $this->status ?? 'active',
            'activity_status' => $this->activity_status,
            'status_emoji' => $this->status_emoji,
            'status_message'  => $this->status_message,
            'is_busy'      => $this->isBusy(),

            // Conversation timing
            'last_message_at'    => $lastMessageTime?->toIso8601String(),
            'last_message_preview' => $lastMessage
                ? \Illuminate\Support\Str::limit($lastMessage->content ?? '', 80)
                : null,
            'conversations_count' => $conversations->count(),

            // Individuals managed by this family admin
            'individuals'  => $individuals->map(fn ($ind) => [
                'id'            => $ind->id,
                'full_name'     => $ind->full_name,
                'date_of_birth' => $ind->date_of_birth?->format('Y-m-d'),
                'age'           => $ind->age,
                'status'        => $ind->status,
                'profile_photo' => $ind->profile_photo,
                'todays_mood'   => $ind->todays_care_pulse,
            ])->values(),

            // Summary info
            'latest_mood' => $latestMood ? [
                'mood'       => $latestMood->mood,
                'check_date' => $latestMood->check_date?->format('Y-m-d'),
            ] : null,

            'latest_care_note_date' => $latestCareNote
                ? $latestCareNote->shift_date?->format('Y-m-d')
                : null,

            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
