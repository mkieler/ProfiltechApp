<?php

namespace Modules\Delivery\External\OpenRouteService\Data;

use Spatie\LaravelData\Data;

class ORSStep extends Data
{
    public function __construct(
        public ?int $id,
        public string $type,
        public string $arrival,
        public int $duration
    ) {}
}
