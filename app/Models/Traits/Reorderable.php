<?php
namespace App\Models\Traits;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use \stdClass;

/**
 * @template T
 */
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

    /**
     * @param T $model
     */
    public function moveInBetween(mixed $a, mixed $b)
    {
        $this->order_numerator   = $a->order_numerator + $b->order_numerator;
        $this->order_denominator = $a->order_denominator + $b->order_denominator;
        $this->save();
    }

    public function order(): Attribute
    {
        return new Attribute(
            get: fn() => $this->order_numerator / $this->order_denominator
        );
    }

    /**
     * @param T $model
     */
    public function moveBefore(mixed $model)
    {
        $order = $model->order;
        $query = $this->query()->reordered()->whereRaw('(order_numerator::FLOAT / order_denominator::FLOAT) < ?', [$order]);

        $count = $query->count();

        if ($count === 0) {
            $standIn                    = new stdClass;
            $standIn->order_numerator   = 0;
            $standIn->order_denominator = 1;
            $this->moveInBetween($standIn, $model);
            return;
        }
        $lastItem = $query->skip($count - 1)->take(1)->first(['order_numerator', 'order_denominator']);

        $this->moveInBetween($lastItem, $model);
    }

    /**
     * @param T $model
     */
    public function moveAfter(mixed $model)
    {
        $order = $model->order;
        $query = $this->query()->reordered()->whereRaw('(order_numerator::FLOAT / order_denominator::FLOAT) > ?', [$order]);

        $firstItem = $query->first(['order_numerator', 'order_denominator']);

        if (! isset($firstItem)) {
            $standIn                    = new stdClass;
            $standIn->order_numerator   = 1;
            $standIn->order_denominator = 0;
            $this->moveInBetween($model, $standIn);
            return;
        }

        $this->moveInBetween($model, $firstItem);
    }
}
