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

    public function addStopToRoute(AddStopToRouteRequest $addStopToRouteRequest): void
    {
        $this->deliveryService->addStopToRoute(
            (int) $addStopToRouteRequest->route_id,
            (int) $addStopToRouteRequest->order_id
        );
    }
}
