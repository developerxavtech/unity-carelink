<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoiceCornerReaction extends Model
{
    use HasFactory;

    public const TYPES = ['heart', 'fire', 'clap'];

    protected $fillable = [
        'voice_corner_post_id',
        'user_id',
        'type',
    ];

    public function post()
    {
        return $this->belongsTo(VoiceCornerPost::class, 'voice_corner_post_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
