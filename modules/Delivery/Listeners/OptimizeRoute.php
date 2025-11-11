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
    public function handle(RouteUpdatedEvent $event): void
    {
        $this->deliveryService->optimizeStopsOnRoute(
            $event->route,
            new ORSVehicle(1, 'driving-hgv')
        );
    }
}
