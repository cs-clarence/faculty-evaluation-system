<?php
namespace App\Models;

use App\Models\Traits\Archivable;
use App\Models\Traits\FullTextSearchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * @mixin IdeHelperSection
 */
class Section extends Model
{
    /** @use HasFactory<\Database\Factories\SectionFactory> */
    use HasFactory, Archivable, FullTextSearchable;
    protected $table    = 'sections';
    protected $fillable = ['year_level', 'code', 'name', 'course_id', 'semester'];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function semesterSections(): HasMany
    {
        return $this->hasMany(SemesterSection::class);
    }

    public function semesters(): HasManyThrough
    {
        return $this->hasManyThrough(Semester::class, SemesterSection::class);
    }

    public function hasDependents()
    {
        $semesterSectionsCount = isset($this->semester_sections_count) ? $this->semester_sections_count : $this->semesterSections()->count();

        if ($semesterSectionsCount > 0) {
            return true;
        }

        return false;
    }
}
