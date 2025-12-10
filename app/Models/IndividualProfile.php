<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndividualProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'family_user_id',
        'first_name',
        'last_name',
        'date_of_birth',
        'pronouns',
        'profile_photo',
        'strengths_abilities',
        'preferences_interests',
        'communication_style',
        'sensory_profile',
        'triggers',
        'calming_strategies',
        'safety_notes',
        'emergency_contacts',
        'medical_info',
        'goals',
        'profile_visibility_settings',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'emergency_contacts' => 'array',
        'goals' => 'array',
        'profile_visibility_settings' => 'array',
    ];

    // Relationships

    /**
     * The family member who manages this profile.
     */
    public function familyAdmin()
    {
        return $this->belongsTo(User::class, 'family_user_id');
    }

    /**
     * Role assignments for this individual (who has access).
     */
    public function roleAssignments()
    {
        return $this->hasMany(RoleAssignment::class);
    }

    /**
     * DSPs assigned to support this individual.
     */
    public function dsps()
    {
        return $this->belongsToMany(User::class, 'role_assignments')
            ->wherePivot('role_type', 'dsp')
            ->withPivot('permissions')
            ->withTimestamps();
    }

    /**
     * All users with access to this individual's profile.
     */
    public function authorizedUsers()
    {
        return $this->belongsToMany(User::class, 'role_assignments')
            ->withPivot('role_type', 'permissions')
            ->withTimestamps();
    }

    /**
     * Care notes for this individual.
     */
    public function careNotes()
    {
        return $this->hasMany(CareNote::class);
    }

    /**
     * Mood checks for this individual.
     */
    public function moodChecks()
    {
        return $this->hasMany(MoodCheck::class);
    }

    /**
     * Messages about this individual.
     */
    public function messages()
    {
        return $this->hasManyThrough(Message::class, Conversation::class);
    }

    // Scopes

    /**
     * Scope to get only active profiles.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get profiles managed by a specific family admin.
     */
    public function scopeManagedBy($query, int $userId)
    {
        return $query->where('family_user_id', $userId);
    }

    /**
     * Scope to get profiles accessible by a user (based on role assignments).
     */
    public function scopeAccessibleBy($query, int $userId)
    {
        return $query->whereHas('roleAssignments', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }

    // Helper Methods

    /**
     * Get the individual's full name.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get the individual's age.
     */
    public function getAgeAttribute(): ?int
    {
        return $this->date_of_birth?->age;
    }

    /**
     * Check if profile is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if a user has access to this profile.
     */
    public function userHasAccess(int $userId): bool
    {
        // Family admin always has access
        if ($this->family_user_id === $userId) {
            return true;
        }

        // Check role assignments
        return $this->roleAssignments()->where('user_id', $userId)->exists();
    }

    /**
     * Get profile completion percentage.
     */
    public function getProfileCompletenessAttribute(): int
    {
        $fields = [
            'date_of_birth',
            'strengths_abilities',
            'preferences_interests',
            'communication_style',
            'sensory_profile',
            'triggers',
            'calming_strategies',
            'safety_notes',
            'emergency_contacts',
            'goals',
        ];

        $completed = 0;
        foreach ($fields as $field) {
            if (!empty($this->$field)) {
                $completed++;
            }
        }

        return round(($completed / count($fields)) * 100);
    }

    /**
     * Get today's CarePulse (average mood for today).
     */
    public function getTodaysCarePulseAttribute(): ?float
    {
        $todaysMoodChecks = $this->moodChecks()
            ->whereDate('check_date', today())
            ->get();

        if ($todaysMoodChecks->isEmpty()) {
            return null;
        }

        return $todaysMoodChecks->avg('rating');
    }

    /**
     * Get recent care notes (last 7 days).
     */
    public function getRecentCareNotesAttribute()
    {
        return $this->careNotes()
            ->where('created_at', '>=', now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
