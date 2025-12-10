<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'user_id',
        'message_type',
        'content',
        'attachments',
        'mentioned_users',
        'tags',
        'read_by',
    ];

    protected $casts = [
        'attachments' => 'array',
        'mentioned_users' => 'array',
        'tags' => 'array',
        'read_by' => 'array',
    ];

    // Relationships

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes

    public function scopeUnreadBy($query, int $userId)
    {
        return $query->where(function($q) use ($userId) {
            $q->whereNull('read_by')
              ->orWhereJsonDoesntContain('read_by', $userId);
        });
    }
}
