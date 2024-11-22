<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperSchoolYearSemester
 */
class SchoolYearSemester extends Model
{
    protected $table = 'school_year_semesters';

    public function school_year(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class);
    }
}
