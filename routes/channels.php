<?php

use App\Models\Conversation;
use App\Models\Ride;
use Illuminate\Support\Facades\Broadcast;

// Authorizes a user to listen on a conversation's private channel: only
// participants (or program staff, who can see every conversation) may join.
Broadcast::channel('conversation.{conversationId}', function ($user, int $conversationId) {
    if ($user->hasRole('program_staff')) {
        return true;
    }

    return Conversation::query()
        ->whereKey($conversationId)
        ->whereHas('participants', fn ($q) => $q->where('user_id', $user->id))
        ->exists();
});

// Voice Corner is a DSP-only community feed — explicitly excludes
// program_staff/coordinators and families, unlike the conversation channel above.
Broadcast::channel('voice-corner', function ($user) {
    return $user->hasRole('dsp');
});

// A user's own personal notification channel — used for events addressed
// directly to them (e.g. an incoming ride request, a ride being accepted
// or rejected) rather than a shared resource like a conversation.
Broadcast::channel('user.{userId}', function ($user, int $userId) {
    return $user->id === $userId;
});

// A ride's shared channel — only its two participants (the family admin who
// booked it and the DSP who accepted it) may listen for status updates.
Broadcast::channel('ride.{rideId}', function ($user, int $rideId) {
    return Ride::query()
        ->whereKey($rideId)
        ->where(fn ($q) => $q->where('family_user_id', $user->id)->orWhere('dsp_user_id', $user->id))
        ->exists();
});
