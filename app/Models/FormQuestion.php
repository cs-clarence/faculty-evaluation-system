<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperFormQuestion
 */
class FormQuestion extends Model
{
    protected $table = 'form_questions';
    public $fillable = ['question', 'description', 'type', 'form_id', 'form_section_id'];

    public function options(): HasMany
    {
        return $this->hasMany(FormQuestionOption::class);
    }
}
