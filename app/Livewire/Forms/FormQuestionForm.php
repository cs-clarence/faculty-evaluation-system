<?php
namespace App\Livewire\Forms;

use App\Models\FormQuestion;
use App\Models\FormQuestionType;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use \DB;

/**
 * @extends parent<FormQuestion>
 */
class FormQuestionForm extends BaseForm
{
    #[Locked]
    public ?int $id = null;
    public ?string $title = null;
    public ?string $type = null;
    public ?string $description = null;
    public ?int $form_id = null;
    public ?float $weight = 1;
    public ?int $form_section_id = null;
    public ?float $essay_value_scale_from = null;
    public ?float $essay_value_scale_to = null;

    public function rules()
    {
        $uniqueTitle = Rule::unique('form_questions')
            ->where('form_section_id', $this->form_id)
            ->where('title', $this->title);

        $rules = [
            'title'           => ['required', 'min:0', 'max:255', isset($this->id) ? $uniqueTitle->ignore($this->id) : $uniqueTitle],
            'description'     => ['nullable', 'max:1025'],
            'type'            => ['required', Rule::enum(FormQuestionType::class)],
            'form_id'         => ['required', 'exists:forms,id'],
            'form_section_id' => ['required', 'exists:form_sections,id'],
            'weight'          => ['required', 'numeric', 'gt:0'],
        ];

        if ($this->type === FormQuestionType::Essay->value) {
            $rules['essay_value_scale_from'] = ['required', 'numeric', 'min:0', 'max:100', 'lt:essay_value_scale_to'];
            $rules['essay_value_scale_to']   = ['required', 'numeric', 'min:0', 'max:100', 'gt:essay_value_scale_from'];
        }

        return $rules;
    }

    public function submit()
    {
        $this->validate();
        DB::transaction(function () {
            if (! isset($this->id)) {
                $question = FormQuestion::create([
                     ...$this->except([
                        'id',
                        'essay_value_scale_from',
                        'essay_value_scale_to',
                    ]),
                    'order_numerator' => 0,
                ]);

            } else {
                $question = FormQuestion::whereId($this->id)->first();
                $question->update($this->except([
                    'id',
                    'form_id',
                    'form_section_id',
                    'essay_value_scale_from',
                    'essay_value_scale_to',
                ]));
            }

            if ($this->type === FormQuestionType::Essay->value) {

                if ($question->essayTypeConfiguration()->exists()) {
                    $question->essayTypeConfiguration()->update([
                        'value_scale_from' => $this->essay_value_scale_from,
                        'value_scale_to'   => $this->essay_value_scale_to,
                    ]);
                } else {
                    $question->essayTypeConfiguration()->create([
                        'value_scale_from' => $this->essay_value_scale_from,
                        'value_scale_to'   => $this->essay_value_scale_to,
                    ]);
                }
            }
        });

    }

    /**
     * @param FormQuestion $model
     **/
    public function set(mixed $model)
    {
        $this->fill([ ...$model->attributesToArray(),
            'essay_value_scale_from' => $model->essayTypeConfiguration?->value_scale_from,
            'essay_value_scale_to'   => $model->essayTypeConfiguration?->value_scale_to,
        ]);
    }
}
