<?php

declare(strict_types=1);

namespace Modules\Shared\Data;

class SearchFilter
{
    public function __construct(
        public string $field,
        public string $value
    ) {}
}
