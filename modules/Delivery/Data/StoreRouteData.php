<?php

namespace Modules\Delivery\Data;

use Illuminate\Support\Carbon;

class StoreRouteData
{
    public function __construct(
        public string $name,
        public Carbon $date,
    ) {}
}
