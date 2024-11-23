<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperSemester
 */
class Semester extends Model
{
    protected $table = 'semesters';

    public function school_year(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class);
    }
}
