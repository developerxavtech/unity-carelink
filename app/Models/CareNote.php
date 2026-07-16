<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'individual_profile_id',
        'family_user_id',
        'dsp_user_id',
        'shift_date',
        'notes',
        'mood',
        'activities',
        'meals',
        'medications',
        'incidents',
    ];

    protected $casts = [
        'shift_date' => 'date',
    ];

    // Relationships

    public function individualProfile()
    {
        return $this->belongsTo(IndividualProfile::class);
    }

    /**
     * The family admin this log was filed against (mobile API daily logs —
     * see DailyLogController — attach directly to the family admin rather
     * than an individual profile).
     */
    public function familyAdmin()
    {
        return $this->belongsTo(User::class, 'family_user_id');
    }

    public function dsp()
    {
        return $this->belongsTo(User::class, 'dsp_user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'dsp_user_id');
    }

    // Scopes

    public function scopeForIndividual($query, int $individualProfileId)
    {
        return $query->where('individual_profile_id', $individualProfileId);
    }

    public function scopeForFamilyAdmin($query, int $familyUserId)
    {
        return $query->where('family_user_id', $familyUserId);
    }

    public function scopeByDsp($query, int $dspUserId)
    {
        return $query->where('dsp_user_id', $dspUserId);
    }

    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
