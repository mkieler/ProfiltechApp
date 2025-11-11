<?php

declare(strict_types=1);

namespace Modules\Delivery\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Delivery\Models\Route;

class RouteUpdatedEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;
    /**
     * Create a new event instance.
     */
    public function __construct(
        public Route $route
    ) {}
}
