<?php

namespace Modules\Delivery\External\OpenRouteService\Data;
class ORSJob
{
    public int $id;
    public array $location;
    public int $service;

    public function __construct(
        int $id,
        float $latitude,
        float $longitude,
        int $service
    ) {
        $this->id = $id;
        $this->location = [$longitude, $latitude];
        $this->service = $service;
    }
}
