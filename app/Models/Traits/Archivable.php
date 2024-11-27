<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait Archivable
{
    /**
     *
     * @param array<int> $ignoreIds
     * @return void
     */
    public function scopeWithoutArchived(Builder $builder, ?array $exceptIds = null)
    {
        $builder->where(function (Builder $builder) use ($exceptIds) {
            $b = $builder->whereNull('archived_at');

            if (isset($exceptIds)) {
                $b = $b->orWhereIn('id', $exceptIds);
            }
        });
    }

    public function archive()
    {
        $this->archived_at = now();
        $this->save();
    }

    public function unarchive()
    {
        $this->archived_at = null;
        $this->save();
    }

    protected function isArchived(): Attribute
    {
        return Attribute::make(get: fn() => isset($this->archived_at));
    }
}
