<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'role_type',
        'organization_id',
        'individual_profile_id',
        'permissions',
        'assigned_by_user_id',
    ];

    protected $casts = [
        'permissions' => 'array',
    ];

    // Role type constants
    const ROLE_FAMILY_ADMIN = 'family_admin';
    const ROLE_INDIVIDUAL = 'individual';
    const ROLE_DSP = 'dsp';
    const ROLE_AGENCY_ADMIN = 'agency_admin';
    const ROLE_PROGRAM_STAFF = 'program_staff';
    const ROLE_CASE_MANAGER = 'case_manager';
    const ROLE_SUPER_ADMIN = 'super_admin';

    /**
     * Get all available role types.
     */
    public static function getRoleTypes(): array
    {
        return [
            self::ROLE_FAMILY_ADMIN,
            self::ROLE_INDIVIDUAL,
            self::ROLE_DSP,
            self::ROLE_AGENCY_ADMIN,
            self::ROLE_PROGRAM_STAFF,
            self::ROLE_CASE_MANAGER,
            self::ROLE_SUPER_ADMIN,
        ];
    }

    // Relationships

    /**
     * The user assigned to this role.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The organization this role belongs to.
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * The individual profile this role has access to.
     */
    public function individualProfile()
    {
        return $this->belongsTo(IndividualProfile::class);
    }

    /**
     * The user who assigned this role.
     */
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by_user_id');
    }

    // Scopes

    /**
     * Scope to get role assignments by role type.
     */
    public function scopeOfType($query, string $roleType)
    {
        return $query->where('role_type', $roleType);
    }

    /**
     * Scope to get role assignments for a specific organization.
     */
    public function scopeForOrganization($query, int $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    /**
     * Scope to get role assignments for a specific individual.
     */
    public function scopeForIndividual($query, int $individualProfileId)
    {
        return $query->where('individual_profile_id', $individualProfileId);
    }

    /**
     * Scope to get family admin roles.
     */
    public function scopeFamilyAdmins($query)
    {
        return $query->where('role_type', self::ROLE_FAMILY_ADMIN);
    }

    /**
     * Scope to get DSP roles.
     */
    public function scopeDsps($query)
    {
        return $query->where('role_type', self::ROLE_DSP);
    }

    /**
     * Scope to get agency admin roles.
     */
    public function scopeAgencyAdmins($query)
    {
        return $query->where('role_type', self::ROLE_AGENCY_ADMIN);
    }

    // Helper Methods

    /**
     * Check if this is a family admin role.
     */
    public function isFamilyAdmin(): bool
    {
        return $this->role_type === self::ROLE_FAMILY_ADMIN;
    }

    /**
     * Check if this is a DSP role.
     */
    public function isDsp(): bool
    {
        return $this->role_type === self::ROLE_DSP;
    }

    /**
     * Check if this is an agency admin role.
     */
    public function isAgencyAdmin(): bool
    {
        return $this->role_type === self::ROLE_AGENCY_ADMIN;
    }

    /**
     * Check if this is a super admin role.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role_type === self::ROLE_SUPER_ADMIN;
    }

    /**
     * Check if user has a specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        if (empty($this->permissions)) {
            return false;
        }

        return in_array($permission, $this->permissions);
    }

    /**
     * Grant a permission to this role.
     */
    public function grantPermission(string $permission): void
    {
        $permissions = $this->permissions ?? [];

        if (!in_array($permission, $permissions)) {
            $permissions[] = $permission;
            $this->permissions = $permissions;
            $this->save();
        }
    }

    /**
     * Revoke a permission from this role.
     */
    public function revokePermission(string $permission): void
    {
        $permissions = $this->permissions ?? [];

        $this->permissions = array_values(array_diff($permissions, [$permission]));
        $this->save();
    }

    /**
     * Get human-readable role name.
     */
    public function getRoleNameAttribute(): string
    {
        return match($this->role_type) {
            self::ROLE_FAMILY_ADMIN => 'Family Admin',
            self::ROLE_INDIVIDUAL => 'Individual',
            self::ROLE_DSP => 'Direct Support Professional',
            self::ROLE_AGENCY_ADMIN => 'Agency Administrator',
            self::ROLE_PROGRAM_STAFF => 'Program Staff',
            self::ROLE_CASE_MANAGER => 'Case Manager',
            self::ROLE_SUPER_ADMIN => 'Super Administrator',
            default => ucwords(str_replace('_', ' ', $this->role_type)),
        };
    }
}
