<?php
namespace App\Models;

use App\Models\Traits\Reorderable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperFormQuestion
 */
class FormQuestion extends Model
{
    use Reorderable;
    protected $table    = 'form_questions';
    protected $fillable = ['title', 'description', 'type', 'form_id', 'form_section_id', 'order_numerator', 'order_denominator'];

    public function options(): HasMany
    {
        return $this->hasMany(FormQuestionOption::class);
    }

    public function essayTypeConfiguration()
    {
        return $this->hasOne(FormQuestionEssayTypeConfiguration::class);
    }

    public function getMaxValue()
    {
        $q = $this;

        if ($q->type === FormQuestionType::Essay->value) {
            $config   = $q->essayTypeConfiguration;
            $maxValue = $config->value_scale_to;
        } else if ($q->type === FormQuestionType::MultipleChoicesSingleSelect->value) {
            $maxValue = $q->options->max('value');
        } else if ($q->type === FormQuestionType::MultipleChoicesMultipleSelect->value) {
            $maxValue = $q->options
                ->filter(fn($i) => $i->value >= 0)
                ->sum('value');
        } else {
            throw new \Exception("Invalid question type '{$q->type}'");
        }

        return $maxValue;
    }

    protected function maxValue(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->getMaxValue()
        )->shouldCache();
    }
}
