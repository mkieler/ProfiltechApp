<?php

namespace Modules\Shared\Requests\Concerns;

trait HasSearchFilter
{
    protected function searchRules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
        ];
    }
}
