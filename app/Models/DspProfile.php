<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DspProfile extends Model
{
    protected $fillable = [
        'user_id',
        'preferred_name',
        'pronouns',
        'roles',
        'communication_preferences',
        'experience_strengths',
        'boundaries_expectations',
        'final_notes',
        'is_verified',
        'verification_code',
        'verification_code_expires_at',
    ];

    protected $casts = [
        'roles' => 'array',
        'communication_preferences' => 'array',
        'is_verified' => 'boolean',
        'verification_code_expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
