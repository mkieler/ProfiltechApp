<?php

declare(strict_types=1);

namespace Modules\Delivery\External\OpenRouteService\Data;

class ORSRoute
{
    public function __construct(
        public array $steps,
        public ORSVehicle $vehicle
    ) {}
}
