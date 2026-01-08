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
        // Without teams, we return all users for now.
        // A direct organization_user pivot should be added.
        return User::query();
    }

    /**
     * Staff members.
     */
    public function staff()
    {
        return User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['program_staff', 'agency_admin']);
        });
    }

    /**
     * DSPs working for this organization.
     */
    public function dsps()
    {
        return User::role('dsp');
    }

    /**
     * Agency admins for this organization.
     */
    public function admins()
    {
        return User::role('agency_admin');
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
