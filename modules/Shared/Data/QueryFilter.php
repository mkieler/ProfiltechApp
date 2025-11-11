<?php

declare(strict_types=1);

namespace Modules\Shared\Data;

class QueryFilter
{
    public function __construct(
        public string $field,
        public string $operator,
        public mixed $value
    ) {}
}
