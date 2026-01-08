<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'profile_photo',
        'status',
        'two_factor_secret',
        'activity_status',
        'status_emoji',
        'status_message',
        'status_busy_until',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status_busy_until' => 'datetime',
        ];
    }

    /**
     * Check if the user is currently busy.
     */
    public function isBusy(): bool
    {
        return $this->activity_status !== null &&
            ($this->status_busy_until === null || $this->status_busy_until->isFuture());
    }

    /**
     * Get the user's status message with emoji.
     */
    public function getFullStatusAttribute(): string
    {
        if (!$this->activity_status) {
            return 'Available';
        }

        $emoji = $this->status_emoji ? "{$this->status_emoji} " : '';
        return "{$emoji}{$this->activity_status}";
    }

    /**
     * Get the user's name (compatibility with tests).
     */
    public function getNameAttribute(): string
    {
        return $this->full_name;
    }

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // Relationships

    /**
     * Individual profiles this user manages (as family admin).
     */
    public function individualProfiles()
    {
        return $this->hasMany(IndividualProfile::class, 'family_user_id');
    }

    /**
     * Accessible individual profiles.
     */
    public function accessibleIndividualProfiles()
    {
        // Without teams, family admin sees their own. 
        // DSPs/Staff might need a new assignment system, but for now we see all or none.
        if ($this->hasRole('family_admin')) {
            return $this->individualProfiles();
        }

        // For DSPs/Staff, we'll return all for now to avoid breaking the dashboard,
        // but this should be refined later with a new assignment system.
        return IndividualProfile::query();
    }

    /**
     * Organizations this user belongs to.
     */
    public function organizations()
    {
        // Simplified to return all for now or empty until a direct relationship is added.
        return Organization::query();
    }

    /**
     * Messages sent by this user.
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Care notes created by this user.
     */
    public function careNotes()
    {
        return $this->hasMany(CareNote::class, 'dsp_user_id');
    }

    /**
     * Mood checks submitted by this user.
     */
    public function moodChecks()
    {
        return $this->hasMany(MoodCheck::class, 'submitted_by_user_id');
    }

    /**
     * Check if user is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Scope to get only active users.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get users by role type.
     */
    public function scopeWithRole($query, string $roleType)
    {
        return $query->role($roleType);
    }

    /**
     * Calendar events created by this user.
     */
    public function calendarEvents()
    {
        return $this->hasMany(CalendarEvent::class);
    }
}
