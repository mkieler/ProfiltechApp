<?php

namespace Modules\Shared\Requests\Concerns;

trait HasPaginationFilter
{
    protected function paginationRules(): array
    {
        return [
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
