<?php

declare(strict_types=1);

namespace Modules\Delivery\External\OpenRouteService\Data;

class ORSVehicle
{
    public array $start;

    public array $end;

    public function __construct(
        public int $id,
        public string $profile
    ) {
        $this->start = [(float) env('OPENROUTESERVICE_START_LONGITUDE'), (float) env('OPENROUTESERVICE_START_LATITUDE')];
        $this->end = [(float) env('OPENROUTESERVICE_START_LONGITUDE'), (float) env('OPENROUTESERVICE_START_LATITUDE')];
    }
}
