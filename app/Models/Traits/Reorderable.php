<?php
namespace App\Models\Traits;

use Exception;
use Illuminate\Database\Eloquent\Builder;

trait Reorderable
{

    public function scopeReordered(Builder $builder, string $direction = 'asc')
    {
        if ($direction === 'asc') {
            return $builder->orderByRaw('COALESCE(order_numerator::FLOAT / order_denominator::FLOAT, 1) ASC');
        } else if ($direction === 'desc') {
            return $builder->orderByRaw('COALESCE(order_numerator::FLOAT / order_denominator::FLOAT, 1) DESC');
        }

        throw new Exception("Invalid direction '{$direction}'");
    }

    public function scopeReorderedAsc(Builder $builder)
    {
        return $this->scopeReordered($builder, 'asc');
    }

    public function scopeReorderedDesc(Builder $builder)
    {
        return $this->scopeReordered($builder, 'desc');
    }
}
