<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * @mixin IdeHelperSection
 */
class Section extends Model
{
    /** @use HasFactory<\Database\Factories\SectionFactory> */
    use HasFactory;
    protected $table = 'sections';

    public function semesterSections(): HasMany
    {
        return $this->hasMany(SemesterSection::class);
    }

    public function semesters(): HasManyThrough
    {
        return $this->hasManyThrough(Semester::class, SemesterSection::class);
    }
}
