<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperSchoolYear
 */
class SchoolYear extends Model
{
    /** @use HasFactory<\Database\Factories\SchoolYearFactory> */
    use HasFactory;
    protected $table = 'school_years';
    protected $fillable = ['year_start', 'year_end'];

    public function semesters(): HasMany
    {
        return $this->hasMany(Semester::class);
    }
}
