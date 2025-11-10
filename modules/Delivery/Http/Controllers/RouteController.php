<?php

namespace Modules\Delivery\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Delivery\Http\Requests\AddStopToRouteRequest;
use Modules\Delivery\Models\Route;
use Modules\Delivery\Services\DeliveryService;

class RouteController
{
    public function __construct(
        public DeliveryService $deliveryService
    ) {}

    public function addStopToRoute(AddStopToRouteRequest $addStopToRouteRequest)
    {
        $this->deliveryService->addStopToRoute(
            $addStopToRouteRequest->route_id, 
            $addStopToRouteRequest->order_id
        );
    }

}
