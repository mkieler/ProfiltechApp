<?php

namespace Modules\Delivery\External\OpenRouteService\Data;

class ORSVehicle
{
    public int $id;
    public string $profile;
    public array $start;
    public array $end;

    public function __construct(
        int $id,
        string $profile
    ) {
        $this->id = $id;
        $this->profile = $profile;
        $this->start = [(float) env('OPENROUTESERVICE_START_LONGITUDE'), (float) env('OPENROUTESERVICE_START_LATITUDE')];
        $this->end = [(float) env('OPENROUTESERVICE_START_LONGITUDE'), (float) env('OPENROUTESERVICE_START_LATITUDE')];
    }
}
