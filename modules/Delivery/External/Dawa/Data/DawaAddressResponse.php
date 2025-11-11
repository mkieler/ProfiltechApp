<?php

declare(strict_types=1);

namespace Modules\Delivery\External\Dawa\Data;

use Spatie\LaravelData\Data;

class DawaAddressResponse extends Data
{
    public function __construct(
        public float $x,
        public float $y,
    ) {}
}
