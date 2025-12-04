<?php

namespace Modules\Shared\Requests\Concerns;

trait HasSortingFilter
{
    protected function sortingRules(): array
    {
        return [
            'sort_by' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'string', 'in:asc,desc'],
        ];
    }
}
