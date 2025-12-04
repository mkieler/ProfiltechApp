<?php

namespace Modules\Shared\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasSortScope
{
    /**
     * Scope a query to sort by a column
     */
    public function scopeSort(Builder $query, ?string $sortBy, string $sortOrder = 'asc'): Builder
    {
        if (!$sortBy) {
            return $query;
        }

        $sortableColumns = $this->getSortableColumns();

        if (!empty($sortableColumns) && !in_array($sortBy, $sortableColumns)) {
            return $query;
        }

        return $query->orderBy($sortBy, $sortOrder);
    }

    /**
     * Define which columns are sortable
     * Override this method in your model or set $sortable property
     */
    protected function getSortableColumns(): array
    {
        return $this->sortable ?? [];
    }
}
