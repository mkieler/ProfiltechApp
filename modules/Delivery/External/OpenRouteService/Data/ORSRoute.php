<?php

declare(strict_types=1);

namespace Modules\Delivery\External\OpenRouteService\Data;

class ORSRoute
{
    /**
     * @param array<int, mixed> $steps
     */
    public function __construct(
        public array $steps,
        public ORSVehicle $vehicle
    ) {}
}
