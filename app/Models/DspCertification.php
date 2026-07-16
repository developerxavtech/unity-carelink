<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DspCertification extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 'current' | 'expiring_soon' | 'expired'
     */
    public function getStatusAttribute(): string
    {
        if (! $this->expires_at) {
            return 'current';
        }

        if ($this->expires_at->isPast()) {
            return 'expired';
        }

        if ($this->expires_at->lte(now()->addDays(120))) {
            return 'expiring_soon';
        }

        return 'current';
    }

    /**
     * Display label matching the mobile UI, e.g. "Current" or "Expires Jun 2026".
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'expired' => 'Expired '.$this->expires_at->format('M Y'),
            'expiring_soon' => 'Expires '.$this->expires_at->format('M Y'),
            default => 'Current',
        };
    }
}
