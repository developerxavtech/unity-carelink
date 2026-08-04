<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ride extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_ACCEPTED = 'accepted';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_CANCELLED = 'cancelled';

    public const STATUS_IN_PROGRESS = 'in_progress';

    public const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'family_user_id',
        'dsp_user_id',
        'individual_profile_id',
        'vehicle_type',
        'status',
        'pickup_address',
        'pickup_latitude',
        'pickup_longitude',
        'destination_address',
        'destination_latitude',
        'destination_longitude',
        'distance_miles',
        'fare',
        'rejection_reason',
        'responded_at',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'pickup_latitude' => 'decimal:7',
        'pickup_longitude' => 'decimal:7',
        'destination_latitude' => 'decimal:7',
        'destination_longitude' => 'decimal:7',
        'distance_miles' => 'decimal:2',
        'fare' => 'decimal:2',
        'responded_at' => 'datetime',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    // Relationships

    public function familyAdmin()
    {
        return $this->belongsTo(User::class, 'family_user_id');
    }

    public function dsp()
    {
        return $this->belongsTo(User::class, 'dsp_user_id');
    }

    public function individualProfile()
    {
        return $this->belongsTo(IndividualProfile::class);
    }

    // Scopes

    public function scopeForFamily($query, int $userId)
    {
        return $query->where('family_user_id', $userId);
    }

    public function scopeForDsp($query, int $userId)
    {
        return $query->where('dsp_user_id', $userId);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', [
            self::STATUS_PENDING,
            self::STATUS_ACCEPTED,
            self::STATUS_IN_PROGRESS,
        ]);
    }
}
