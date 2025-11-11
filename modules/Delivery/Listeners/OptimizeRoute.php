<?php

declare(strict_types=1);

namespace Modules\Delivery\Listeners;

use Modules\Delivery\Events\RouteUpdatedEvent;
use Modules\Delivery\External\OpenRouteService\Data\ORSVehicle;
use Modules\Delivery\Services\DeliveryService;

class OptimizeRoute
{
    public function __construct(
        protected DeliveryService $deliveryService
    ) {}

    /**
     * Handle the event.
     */
    public function handle(RouteUpdatedEvent $routeUpdatedEvent): void
    {
        $this->deliveryService->optimizeStopsOnRoute(
            $routeUpdatedEvent->route,
            new ORSVehicle(1, 'driving-hgv')
        );
    }
}
