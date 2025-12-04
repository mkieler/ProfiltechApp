<?php

namespace Modules\Shared\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasSearchScope
{
    /**
     * Scope a query to search across specified columns
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (!$search) {
            return $query;
        }

        $searchableColumns = $this->getSearchableColumns();

        if (empty($searchableColumns)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($search, $searchableColumns) {
            foreach ($searchableColumns as $column) {
                $q->orWhere($column, 'like', "%{$search}%");
            }
        });
    }

    /**
     * Define which columns are searchable
     * Override this method in your model
     */
    protected function getSearchableColumns(): array
    {
        return $this->searchable ?? [];
    }
}
