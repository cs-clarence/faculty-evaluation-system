<?php
namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

if (! function_exists('addFullTextQuery')) {
    function addFullTextQuery($builder, $columns, $searchText, $or = false)
    {
        return $or ? $builder->orWhereFullText($columns, $searchText) : $builder->whereFullText($columns, $searchText);
    }
}

if (! function_exists('buildQuery')) {
    function buildQuery($builder, $columns, $relations, $searchText)
    {
        $or = false;
        if (count($columns) === 0 && count($relations) === 0) {
            return $builder;
        }

        if (count($columns) > 0) {
            $builder = addFullTextQuery($builder, $columns, $searchText, $or);
            $or      = true;
        }

        if (count($relations) > 0) {
            foreach ($relations as $relation => $query) {
                $rels = $query['relations'] ?? [];
                $cols = $query['columns'] ?? [];

                if (count($cols) === 0 && count($rels) === 0) {
                    continue;
                }

                if ($or) {
                    $builder = $builder->orWhereHas($relation, fn($builder) => buildQuery($builder, $cols, $rels, $searchText));
                } else {
                    $builder = $builder->whereHas($relation, fn($builder) => buildQuery($builder, $cols, $rels, $searchText));
                }

                $or = true;
            }
        }

        return $builder;
    }
}

trait FullTextSearchable
{

    public function scopeFullTextSearch(Builder $builder, array $query, string $searchText)
    {
        $columns    = $query['columns'] ?? [];
        $relations  = $query['relations'] ?? [];
        $searchText = trim($searchText);

        return buildQuery($builder, $columns, $relations, $searchText);
    }
}
