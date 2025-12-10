<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'scope_type',
        'scope_id',
        'name',
        'participants',
        'settings',
    ];

    protected $casts = [
        'participants' => 'array',
        'settings' => 'array',
    ];

    // Relationships

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // Scopes

    public function scopeForUser($query, int $userId)
    {
        return $query->where(function($q) use ($userId) {
            $q->whereJsonContains('participants', $userId)
              ->orWhereHas('messages', function($mq) use ($userId) {
                  $mq->where('user_id', $userId);
              });
        });
    }
}
