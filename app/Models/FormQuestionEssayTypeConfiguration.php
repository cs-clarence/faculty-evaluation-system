<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperFormQuestionEssayTypeConfiguration
 */
class FormQuestionEssayTypeConfiguration extends Model
{
    protected $table = 'form_question_essay_type_configurations';
    public $fillable = ['value_scale_from', 'value_scale_to', 'form_question_id'];
}
