<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperSemesterSection
 */
class SemesterSection extends Model
{
    //
    protected $table    = 'semester_sections';
    protected $fillable = ['semester_id', 'section_id'];

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }
}
