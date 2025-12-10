<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

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
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
        ];
    }

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $roleType): bool
    {
        return $this->roleAssignments()->where('role_type', $roleType)->exists();
    }

    /**
     * Check if user has any of the specified roles.
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->roleAssignments()->whereIn('role_type', $roles)->exists();
    }

    /**
     * Get user's primary role (first role assignment).
     */
    public function getPrimaryRoleAttribute(): ?string
    {
        return $this->roleAssignments()->first()?->role_type;
    }

    // Relationships

    /**
     * Role assignments for this user.
     */
    public function roleAssignments()
    {
        return $this->hasMany(RoleAssignment::class);
    }

    /**
     * Individual profiles this user manages (as family admin).
     */
    public function individualProfiles()
    {
        return $this->hasMany(IndividualProfile::class, 'family_user_id');
    }

    /**
     * Organizations this user belongs to (through role assignments).
     */
    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'role_assignments')
            ->withPivot('role_type', 'permissions')
            ->withTimestamps();
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
        return $query->whereHas('roleAssignments', function ($q) use ($roleType) {
            $q->where('role_type', $roleType);
        });
    }
}
