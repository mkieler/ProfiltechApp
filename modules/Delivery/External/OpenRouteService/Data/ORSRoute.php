<?php

namespace Modules\Delivery\External\OpenRouteService\Data;

class ORSRoute
{
    public function __construct(
        public array $steps,
        public ORSVehicle $vehicle
    ) {}
}
