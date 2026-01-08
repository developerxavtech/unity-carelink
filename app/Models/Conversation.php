<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'individual_profile_id',
        'subject',
        'type',
        'scope_type',
        'scope_id',
        'name',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    // Relationships

    public function participants()
    {
        return $this->belongsToMany(User::class, 'conversation_participants')
            ->withPivot('last_read_at')
            ->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function individualProfile()
    {
        return $this->belongsTo(IndividualProfile::class);
    }

    // Scopes

    public function scopeForUser($query, int $userId)
    {
        return $query->whereHas('participants', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }

    // Helpers

    public function addParticipant(int $userId)
    {
        return $this->participants()->syncWithoutDetaching([$userId]);
    }

    public function removeParticipant(int $userId)
    {
        return $this->participants()->detach($userId);
    }
}
