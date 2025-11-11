<?php

declare(strict_types=1);

namespace Modules\Delivery\Services;

use Illuminate\Database\Eloquent\Collection;
use Modules\Delivery\Enums\DeliveryStatus;
use Modules\Delivery\Events\RouteUpdatedEvent;
use Modules\Delivery\External\OpenRouteService\Data\ORSVehicle;
use Modules\Delivery\External\OpenRouteService\ORSClient;
use Modules\Delivery\Models\Route;

class DeliveryService
{
    public function scheduleDelivery(array $details)
    {
        // Logic to schedule a delivery
    }

    public function getRoutes(int $perPage = 20, bool $withCompleted = false)
    {
        return Route::when(
            ! $withCompleted,
            fn ($query) => $query->where('status', '!=', DeliveryStatus::COMPLETED)
        )->paginate($perPage);
    }

    public function addStopToRoute(int $routeId, int $orderId)
    {
        $route = Route::find($routeId);
        $lastSequence = $route->stops()->max('sequence') ?? 0;
        $route->stops()->create([
            'order_id' => $orderId,
            'sequence' => $lastSequence + 1,
        ]);
        RouteUpdatedEvent::dispatch($route);
    }

    /**
     * @param  Collection<Route>  $stops
     */
    public function optimizeStopsOnRoute(Route $route, ORSVehicle $vehicle)
    {
        $orsJobs = $route->getStopsAsORSJobs();
        $orsRouteSteps = ORSClient::optimizeRoute($orsJobs, $vehicle);
        foreach ($orsRouteSteps as $index => $step) {
            if (! $step->id) {
                continue;
            }
            $stop = $route->stops->firstWhere('id', $step->id);
            $stop->update(['sequence' => $index + 1]);
        }
    }
}
