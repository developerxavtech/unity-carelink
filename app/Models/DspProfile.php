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
        'latitude',
        'longitude',
        'location_updated_at',
        'is_available',
    ];

    protected $casts = [
        'roles' => 'array',
        'communication_preferences' => 'array',
        'is_verified' => 'boolean',
        'verification_code_expires_at' => 'datetime',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'location_updated_at' => 'datetime',
        'is_available' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A DSP is only surfaced in ride search when available with a location
     * shared recently — see config('rides.location_freshness_minutes').
     */
    public function scopeAvailableWithFreshLocation($query)
    {
        return $query->where('is_available', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('location_updated_at', '>=', now()->subMinutes(config('rides.location_freshness_minutes')));
    }
}
