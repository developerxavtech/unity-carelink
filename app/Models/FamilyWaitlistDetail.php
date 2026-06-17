<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FamilyWaitlistDetail extends Model
{
    protected $fillable = [
        'user_id',
        'city',
        'zip_code',
        'communication_method',
        'care_profiles',
        'pilot_questions',
        'usage_intent_rides_pilot',
        'usage_intent_community_only',
        'feedback_transportation',
        'feedback_wants',
        'pilot_acknowledged',
        'step_reached',
    ];

    protected function casts(): array
    {
        return [
            'care_profiles' => 'array',
            'pilot_questions' => 'array',
            'usage_intent_rides_pilot' => 'boolean',
            'usage_intent_community_only' => 'boolean',
            'pilot_acknowledged' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
