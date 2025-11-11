<?php

declare(strict_types=1);

namespace Modules\Delivery\Models\traits;

use Illuminate\Support\Collection;
use Modules\Delivery\External\OpenRouteService\Data\ORSJob;

trait InteractsWithOpenRoute
{
    /**
     * @return Collection<int, ORSJob>
     */
    public function getStopsAsORSJobs(): Collection
    {
        return $this->stops->map(fn ($stop): ORSJob => new ORSJob(
            id: $stop->id,
            latitude: $stop->latitude ?? 0.0,
            longitude: $stop->longitude ?? 0.0,
            service: $stop->service_time ?? 0
        ));
    }
}
