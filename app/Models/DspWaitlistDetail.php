<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DspWaitlistDetail extends Model
{
    protected $fillable = [
        'user_id',
        'city_service_area',
        'has_drivers_license',
        'has_auto_insurance',
        'has_reliable_vehicle',
        'comfort_transporting_disabilities',
        'availability',
        'max_distance',
        'comfort_levels',
        'experience',
        'certifications',
        'message_to_families',
        'usage_intent_driver_pilot',
        'usage_intent_peer_network_only',
        'pilot_questions',
        'pilot_acknowledged',
        'step_reached',
    ];

    protected function casts(): array
    {
        return [
            'has_drivers_license' => 'boolean',
            'has_auto_insurance' => 'boolean',
            'has_reliable_vehicle' => 'boolean',
            'availability' => 'array',
            'comfort_levels' => 'array',
            'pilot_questions' => 'array',
            'usage_intent_driver_pilot' => 'boolean',
            'usage_intent_peer_network_only' => 'boolean',
            'pilot_acknowledged' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
