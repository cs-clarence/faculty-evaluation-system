<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperFormQuestionOption
 */
class FormQuestionOption extends Model
{
    //
    protected $table = 'form_question_options';
    public $fillable = ['name', 'value', 'interpretation', 'form_question_id'];
}
