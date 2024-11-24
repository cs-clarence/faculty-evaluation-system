<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * @mixin IdeHelperSemester
 */
class Semester extends Model
{
    protected $table = 'semesters';
    protected $fillable = ['semester', 'school_year_id'];

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function semesterSections(): HasMany
    {
        return $this->hasMany(SemesterSection::class);
    }

    public function sections(): HasManyThrough
    {
        return $this->hasManyThrough(Section::class, SemesterSection::class);
    }
}
