<?php

namespace Modules\Delivery\Services;

use Modules\Delivery\Data\StoreRouteData;
use Modules\Delivery\Events\RouteUpdatedEvent;
use Illuminate\Database\Eloquent\Collection;
use Modules\Delivery\Enums\DeliveryStatus;
use Modules\Delivery\Events\StopsUpdatedEvent;
use Modules\Delivery\External\OpenRouteService\Data\ORSVehicle;
use Modules\Delivery\External\OpenRouteService\ORSClient;
use Modules\Delivery\Models\Route;
use Modules\Delivery\Models\Stop;

class DeliveryService
{
    public function getRoute(int $id): Route
    {
        return Route::findOrFail($id);
    }

    public function getRoutes(int $perPage = 20, bool $withCompleted = false)
    {
        return Route::when(!$withCompleted, fn($query) =>
            $query->where('status', '!=', DeliveryStatus::COMPLETED)
        )->paginate($perPage);
    }

    public function create(StoreRouteData $data): Route
    {
        return Route::create([
            'name' => $data->name,
            'date' => $data->date,
            'status' => DeliveryStatus::DRAFT,
        ]);
    }

    public function update(int $id, StoreRouteData $data): Route
    {
        $route = Route::findOrFail($id);
        $route->update([
            'name' => $data->name,
            'date' => $data->date,
        ]);
        RouteUpdatedEvent::dispatch($route);
        return $route;
    }

    public function deleteRoute(int $id)
    {
        $route = Route::findOrFail($id);
        $route->delete();
    }

    public function getStopsForRoute(int $routeId): Collection
    {
        return Stop::where('route_id', $routeId)->get();
    }

    public function addStopToRoute(int $routeId, int $orderId)
    {
        $route = Route::find($routeId);
        $lastSequence = $route->stops()->max('sequence') ?? 0;
        $route->stops()->create([
            'order_id' => $orderId,
            'sequence' => $lastSequence + 1,
            'status' => DeliveryStatus::DRAFT,
        ]);
        RouteUpdatedEvent::dispatch($route);
        StopsUpdatedEvent::dispatch($route);
    }

    public function removeStopFromRoute(int $routeId, int $stopId)
    {
        $stop = Stop::with('route')->where(['route_id' => $routeId, 'id' => $stopId])->firstOrFail();
        $stop->delete();
        RouteUpdatedEvent::dispatch($stop->route);
        StopsUpdatedEvent::dispatch($stop->route);
    }

    /**
     * @param Collection<Route> $stops
     */
    public function optimizeStopsOnRoute(Route $route, ORSVehicle $vehicle)
    {
        $orsJobs = $route->getStopsAsORSJobs();
        $orsRouteSteps = ORSClient::optimizeRoute($orsJobs, $vehicle);
        foreach ($orsRouteSteps as $index => $step) {
            if (!$step->id) continue;
            $stop = $route->stops->firstWhere('id', $step->id);
            $stop->update(['sequence' => $index + 1]);
        }
    }
}
