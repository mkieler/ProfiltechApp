<?php

namespace Modules\Shared\Data;

use Illuminate\Http\Request;

class QueryFilters
{
    public function __construct(
        public readonly ?string $search = null,
        public readonly ?string $sortBy = null,
        public readonly string $sortOrder = 'asc',
        public readonly int $perPage = 20,
        public readonly array $additionalFilters = []
    ) {}

    /**
     * Create DTO from Request
     */
    public static function fromRequest(Request $request): static
    {
        return new static(
            search: $request->input('search'),
            sortBy: $request->input('sort_by'),
            sortOrder: $request->input('sort_order', 'asc'),
            perPage: $request->integer('per_page', 20),
            additionalFilters: $request->except(['search', 'sort_by', 'sort_order', 'per_page'])
        );
    }

    /**
     * Get a specific additional filter value
     */
    public function getFilter(string $key, mixed $default = null): mixed
    {
        return $this->additionalFilters[$key] ?? $default;
    }
}
