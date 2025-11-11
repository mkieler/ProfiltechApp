<?php

declare(strict_types=1);

namespace Modules\Delivery\Http\Controllers;

use Modules\Delivery\Http\Requests\AddStopToRouteRequest;
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
