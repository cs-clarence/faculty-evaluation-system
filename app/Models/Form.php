<?php
namespace App\Models;

use App\Models\Traits\Archivable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use \DB;

/**
 * @mixin IdeHelperForm
 */
class Form extends Model
{
    /** @use HasFactory<\Database\Factories\FormFactory> */
    use HasFactory, Archivable;

    protected $table = 'forms';
    public $fillable = ['name', 'description'];

    public function sections()
    {
        return $this->hasMany(FormSection::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(FormQuestion::class);
    }

    public function submissionPeriods(): HasMany
    {
        return $this->hasMany(FormSubmissionPeriod::class);
    }

    public function hasDependents(): bool
    {
        $spCount = isset($this->submission_periods_count) ? $this->submission_periods_count : $this->submissionPeriods()->count();

        if ($spCount > 0) {
            return true;
        }

        return false;
    }

    public function duplicate(): Form
    {
        return DB::transaction(function () {
            $newForm       = $this->replicate();
            $newForm->name = $this->name . ' (Copy)';
            $newForm->save();

            foreach ($this->sections as $section) {
                $newSection                  = $section->replicate();
                $newSection->order_numerator = 0;
                $newForm->sections()->save($newSection);

                foreach ($section->questions as $question) {
                    $newQuestion                  = $question->replicate();
                    $newQuestion->order_numerator = 0;
                    $newQuestion->form_id         = $newForm->id;
                    $newSection->questions()->save($newQuestion);

                    $options = [];
                    foreach ($question->options as $option) {
                        $newOption                  = $option->replicate();
                        $newOption->order_numerator = 0;
                        $options[]                  = $newOption;
                    }

                    $newQuestion->options()->saveMany($options);
                    $essayTypeConfig = $question->essayTypeConfiguration;
                    if (isset($essayTypeConfig)) {
                        $newEssayTypeConfiguration = $essayTypeConfig->replicate();

                        $newQuestion->essayTypeConfiguration()->save($newEssayTypeConfiguration);
                    }
                }
            }

            return $newForm;
        });
    }
}
