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
     * @param  Collection<ORSJob>  $orsJobs
     * @return Collection<ORSStep>
     */
    public static function optimizeRoute(Collection $orsJobs, ORSVehicle $orsVehicle): Collection
    {
        $steps = Http::withHeaders([
            'Authorization' => env('OPENROUTESERVICE_API_KEY'),
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
