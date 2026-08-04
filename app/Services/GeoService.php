<?php

namespace App\Services;

class GeoService
{
    private const EARTH_RADIUS_MILES = 3958.8;

    /**
     * Great-circle distance between two coordinates, in miles.
     */
    public static function distanceInMiles(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($lonDelta / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round(self::EARTH_RADIUS_MILES * $c, 2);
    }

    /**
     * Rough ETA in minutes for a given distance, assuming a flat average
     * travel speed. This is an estimate, not a routed/traffic-aware ETA.
     */
    public static function etaMinutes(float $miles, ?float $averageSpeedMph = null): int
    {
        $averageSpeedMph ??= config('rides.average_speed_mph', 20);

        if ($averageSpeedMph <= 0) {
            return 0;
        }

        return (int) max(1, round(($miles / $averageSpeedMph) * 60));
    }
}
