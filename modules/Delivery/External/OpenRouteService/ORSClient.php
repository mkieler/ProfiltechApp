<?php

declare(strict_types=1);

namespace Modules\Delivery\External\OpenRouteService;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Modules\Delivery\External\OpenRouteService\Data\ORSJob;
use Modules\Delivery\External\OpenRouteService\Data\ORSStep;
use Modules\Delivery\External\OpenRouteService\Data\ORSVehicle;

class ORSClient
{
    /**
     * @param  Collection<int, ORSJob>  $orsJobs
     * @return Collection<int, ORSStep>
     */
    public static function optimizeRoute(Collection $orsJobs, ORSVehicle $orsVehicle): Collection
    {
        $apiKey = config('services.openrouteservice.api_key', '');

        $steps = Http::withHeaders([
            'Authorization' => $apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.openrouteservice.org/optimization', [
            'jobs' => $orsJobs,
            'vehicles' => [
                $orsVehicle,
            ],
        ])->collect('routes.0.steps');

        return $steps->map(fn ($step): ORSStep => ORSStep::from($step));
    }
}
