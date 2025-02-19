<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperFormSubmissionAnswer
 */
class FormSubmissionAnswer extends Model
{
    protected $table    = 'form_submission_answers';
    protected $fillable = ['form_submission_id', 'form_question_id', 'value', 'text', 'interpretation', 'reason'];
    protected $appends  = ['max_value'];

    public function selectedOptions()
    {
        return $this->hasMany(FormSubmissionAnswerSelectedOption::class);
    }

    public function formQuestion()
    {
        return $this->belongsTo(FormQuestion::class);
    }

    public function getMaxValue()
    {
        return $this->formQuestion->getMaxValue();
    }

    protected function maxValue(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->getMaxValue()
        )->shouldCache();
    }
}
