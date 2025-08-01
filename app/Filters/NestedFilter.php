<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class NestedFilter
{
    protected Builder $query;
    protected array $filters;

    public function __construct(Builder $query, array $filters)
    {
        $this->query = $query;
        $this->filters = $filters;
    }

    public function apply(): Builder
    {
        foreach ($this->filters as $key => $value) {
            $this->applyFilter($key, $value);
        }

        return $this->query;
    }

    protected function applyFilter(string $key, $value): void
    {
        $parts = explode('.', $key);

        if (count($parts) === 1) {
            $this->query->where($parts[0], '=', $value);
            return;
        }

        $column = array_pop($parts);
        $relation = implode('.', $parts);

        $this->query->whereHas($relation, function (Builder $q) use ($column, $value) {
            $q->where($column, '=', $value);
        });
    }
}
