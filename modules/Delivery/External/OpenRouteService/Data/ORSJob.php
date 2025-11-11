<?php

declare(strict_types=1);

namespace Modules\Delivery\External\OpenRouteService\Data;

class ORSJob
{
    /** @var array<int, float> */
    public array $location;

    public function __construct(
        public int $id,
        float $latitude,
        float $longitude,
        public int $service
    ) {
        $this->location = [$longitude, $latitude];
    }
}
