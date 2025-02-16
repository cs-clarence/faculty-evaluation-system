<?php
namespace App\Models;

use App\Models\Traits\Reorderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperFormQuestion
 */
class FormQuestion extends Model
{
    use Reorderable;
    protected $table = 'form_questions';
    public $fillable = ['title', 'description', 'type', 'form_id', 'form_section_id', 'order_numerator', 'order_denominator'];

    public function options(): HasMany
    {
        return $this->hasMany(FormQuestionOption::class);
    }

    public function essayTypeConfiguration()
    {
        return $this->hasOne(FormQuestionEssayTypeConfiguration::class);
    }
}
