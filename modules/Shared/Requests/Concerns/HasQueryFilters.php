<?php

namespace Modules\Shared\Requests\Concerns;

use Modules\Shared\Data\QueryFilters;

trait HasQueryFilters
{
    /**
     * Convert request to QueryFilters
     */
    public function toQueryFilters(): QueryFilters
    {
        return QueryFilters::fromRequest($this);
    }
}
