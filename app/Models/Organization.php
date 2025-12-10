<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'address',
        'city',
        'state',
        'zip',
        'phone',
        'email',
        'logo',
        'description',
        'hours_of_operation',
        'license_number',
        'accreditation_info',
        'settings',
        'status',
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    // Relationships

    /**
     * Users belonging to this organization.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_assignments')
            ->withPivot('role_type', 'permissions')
            ->withTimestamps();
    }

    /**
     * Role assignments for this organization.
     */
    public function roleAssignments()
    {
        return $this->hasMany(RoleAssignment::class);
    }

    /**
     * Staff members (users with role assignments to this org).
     */
    public function staff()
    {
        return $this->users();
    }

    /**
     * DSPs working for this organization.
     */
    public function dsps()
    {
        return $this->belongsToMany(User::class, 'role_assignments')
            ->wherePivot('role_type', 'dsp')
            ->withPivot('permissions')
            ->withTimestamps();
    }

    /**
     * Agency admins for this organization.
     */
    public function admins()
    {
        return $this->belongsToMany(User::class, 'role_assignments')
            ->wherePivot('role_type', 'agency_admin')
            ->withPivot('permissions')
            ->withTimestamps();
    }

    // Scopes

    /**
     * Scope to get only active organizations.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get organizations by type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to get agencies.
     */
    public function scopeAgencies($query)
    {
        return $query->where('type', 'agency');
    }

    /**
     * Scope to get programs.
     */
    public function scopePrograms($query)
    {
        return $query->where('type', 'program');
    }

    // Helper Methods

    /**
     * Check if organization is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if organization is an agency.
     */
    public function isAgency(): bool
    {
        return $this->type === 'agency';
    }

    /**
     * Check if organization is a program.
     */
    public function isProgram(): bool
    {
        return $this->type === 'program';
    }

    /**
     * Get full address string.
     */
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->zip,
        ]);

        return implode(', ', $parts);
    }
}
