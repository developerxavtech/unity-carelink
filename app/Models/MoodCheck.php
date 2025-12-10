<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoodCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'individual_profile_id',
        'submitted_by_user_id',
        'mood',
        'notes',
        'check_date',
    ];

    protected $casts = [
        'check_date' => 'date',
    ];

    // Relationships

    public function individualProfile()
    {
        return $this->belongsTo(IndividualProfile::class);
    }

    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by_user_id');
    }

    // Scopes

    public function scopeForIndividual($query, int $individualProfileId)
    {
        return $query->where('individual_profile_id', $individualProfileId);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('check_date', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('check_date', [now()->startOfWeek(), now()->endOfWeek()]);
    }
}
