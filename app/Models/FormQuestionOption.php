<?php
namespace App\Models;

use App\Models\Traits\Reorderable;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperFormQuestionOption
 */
class FormQuestionOption extends Model
{
    use Reorderable;
    //
    protected $table = 'form_question_options';
    public $fillable = ['label', 'value', 'interpretation', 'form_question_id', 'order_numerator', 'order_denominator'];
}
