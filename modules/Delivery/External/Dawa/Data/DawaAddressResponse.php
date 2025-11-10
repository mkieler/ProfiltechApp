<?php

namespace Modules\Delivery\External\Dawa\Data;

use Spatie\LaravelData\Data;

class DawaAddressResponse extends Data
{
    public function __construct(
        public float $x,
        public float $y,
    ) {}
}
