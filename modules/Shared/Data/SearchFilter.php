<?php

namespace Modules\Shared\Data;

class SearchFilter
{
    public function __construct(
        public string $field,
        public string $value
    ) {}
}
