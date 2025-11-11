<?php

declare(strict_types=1);

namespace Modules\Delivery\Models\traits;

use Modules\Delivery\External\OpenRouteService\Data\ORSJob;

trait InteractsWithOpenRoute
{
    public function getStopsAsORSJobs()
    {
        return $this->stops->map(fn($stop): ORSJob => new ORSJob(
            id: $stop->id,
            latitude: $stop->latitude,
            longitude: $stop->longitude,
            service: $stop->service_time
        ));
    }
}
