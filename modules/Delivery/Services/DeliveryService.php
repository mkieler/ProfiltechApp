<?php

declare(strict_types=1);

namespace Modules\Delivery\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Delivery\Enums\DeliveryStatus;
use Modules\Delivery\Events\RouteUpdatedEvent;
use Modules\Delivery\External\OpenRouteService\Data\ORSVehicle;
use Modules\Delivery\External\OpenRouteService\ORSClient;
use Modules\Delivery\Models\Route;

class DeliveryService
{
    /**
     * @param array<string, mixed> $details
     */
    public function scheduleDelivery(array $details): void
    {
        // Logic to schedule a delivery
    }

    /**
     * @return LengthAwarePaginator<Route>
     */
    public function getRoutes(int $perPage = 20, bool $withCompleted = false): LengthAwarePaginator
    {
        return Route::when(
            ! $withCompleted,
            fn ($query) => $query->where('status', '!=', DeliveryStatus::COMPLETED)
        )->paginate($perPage);
    }

    public function addStopToRoute(int $routeId, int $orderId): void
    {
        $route = Route::find($routeId);

        if ($route === null) {
            return;
        }

        $lastSequence = (int) $route->stops()->max('sequence');
        $route->stops()->create([
            'order_id' => $orderId,
            'sequence' => $lastSequence + 1,
        ]);
        RouteUpdatedEvent::dispatch($route);
    }

    public function optimizeStopsOnRoute(Route $route, ORSVehicle $orsVehicle): void
    {
        $orsJobs = $route->getStopsAsORSJobs();
        $orsRouteSteps = ORSClient::optimizeRoute($orsJobs, $orsVehicle);
        foreach ($orsRouteSteps as $index => $step) {
            if (! $step->id) {
                continue;
            }

            $stop = $route->stops->firstWhere('id', $step->id);

            if ($stop !== null) {
                $stop->update(['sequence' => $index + 1]);
            }
        }
    }
}
