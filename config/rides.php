<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Vehicle Types
    |--------------------------------------------------------------------------
    |
    | Fare = base_fare + (distance_miles * per_mile). average_speed_mph is
    | used only to estimate a DSP's ETA to the pickup point when searching
    | for nearby DSPs — it does not affect fare.
    |
    */

    'vehicle_types' => [
        'minivan' => [
            'label' => 'Minivans',
            'base_fare' => 5.00,
            'per_mile' => 2.50,
        ],
        'full_size' => [
            'label' => 'Full-Size',
            'base_fare' => 7.00,
            'per_mile' => 3.00,
        ],
        'suv' => [
            'label' => 'SUV',
            'base_fare' => 6.00,
            'per_mile' => 2.75,
        ],
    ],

    // Assumed average travel speed used to estimate ETA (minutes) from distance.
    'average_speed_mph' => 20,

    // How far (miles) to search for available DSPs around the pickup point.
    'search_radius_miles' => 15,

    // A DSP's shared location older than this is treated as stale and
    // excluded from search results.
    'location_freshness_minutes' => 30,

];
