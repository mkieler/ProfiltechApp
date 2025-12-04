<?php

namespace Modules\Delivery\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Modules\Delivery\Data\StoreRouteData;
use Modules\Delivery\Http\Requests\AddStopToRouteRequest;
use Modules\Delivery\Http\Requests\CreateOrUpdateRouteRequest;
use Modules\Delivery\Models\Route;
use Modules\Delivery\Services\DeliveryService;

class RouteController
{
    public function __construct(
        public DeliveryService $deliveryService
    ) {}

    public function list()
    {
        $routes = $this->deliveryService->getRoutes();
        return $routes;
    }

    public function details(int $id)
    {
        return $this->deliveryService->getRoute($id);
    }

    public function create(CreateOrUpdateRouteRequest $request)
    {
        return $this->deliveryService->create(
            new StoreRouteData(
                name: $request->input('name'),
                date: Carbon::parse($request->input('date')),
            )
        );
    }

    public function update(int $id, CreateOrUpdateRouteRequest $request)
    {
        $route = $this->deliveryService->update(
            $id,
            new StoreRouteData(
                name: $request->input('name'),
                date: Carbon::parse($request->input('date')),
            )
        );
        return $route;
    }

    public function delete(int $id)
    {
        $this->deliveryService->deleteRoute($id);
    }

    public function stops(int $id)
    {
        return $this->deliveryService->getStopsForRoute($id);
    }

    public function addStopToRoute(int $id, AddStopToRouteRequest $addStopToRouteRequest)
    {
        $this->deliveryService->addStopToRoute(
            $id, 
            $addStopToRouteRequest->order_id
        );
    }

    public function removeStopFromRoute(int $id, int $stopId)
    {
        $this->deliveryService->removeStopFromRoute($id, $stopId);
    }

}
