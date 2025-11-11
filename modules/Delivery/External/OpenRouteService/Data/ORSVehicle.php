<?php

declare(strict_types=1);

namespace Modules\Delivery\External\OpenRouteService\Data;

class ORSVehicle
{
    /** @var array<int, float> */
    public array $start;

    /** @var array<int, float> */
    public array $end;

    public function __construct(
        public int $id,
        public string $profile
    ) {
        $startLongitude = (float) config('services.openrouteservice.start_longitude', 0.0);
        $startLatitude = (float) config('services.openrouteservice.start_latitude', 0.0);

        $this->start = [$startLongitude, $startLatitude];
        $this->end = [$startLongitude, $startLatitude];
    }
}
