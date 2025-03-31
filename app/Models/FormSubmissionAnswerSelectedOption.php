<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin IdeHelperFormSubmissionAnswerSelectedOption
 */
class FormSubmissionAnswerSelectedOption extends Model
{
    protected $table = 'form_submission_answer_selected_options';
    public $fillable = ['form_submission_answer_id', 'form_question_option_id'];

    /**
     * Summary of option
     * @return HasOne<FormQuestionOption>
     */
    public function option(): HasOne
    {
        return $this->hasOne(FormQuestionOption::class, 'id', 'form_question_option_id');
    }

    /**
     * Summary of option
     * @return HasOne<FormSubmissionAnswer>
     */
    public function answer(): HasOne
    {
        return $this->hasOne(FormSubmissionAnswer::class, 'id', 'form_submission_answer_id');
    }
}
