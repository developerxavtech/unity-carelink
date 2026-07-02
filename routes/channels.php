<?php

use App\Models\Conversation;
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
