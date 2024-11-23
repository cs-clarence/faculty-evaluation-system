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
    protected $fillable = ['semester', 'school_year_id'];

    public function school_year(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class);
    }
}
