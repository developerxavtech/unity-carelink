<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoiceCornerPost extends Model
{
    use HasFactory;

    public const TAGS = ['win', 'vent', 'motivation', 'question'];

    protected $fillable = [
        'user_id',
        'tag',
        'content',
        'is_anonymous',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reactions()
    {
        return $this->hasMany(VoiceCornerReaction::class);
    }
}
