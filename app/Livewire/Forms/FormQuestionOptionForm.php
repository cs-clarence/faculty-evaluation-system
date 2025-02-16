<?php
namespace App\Livewire\Forms;

use App\Models\FormQuestionOption;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;

class FormQuestionOptionForm extends BaseForm
{
    #[Locked]
    public ?int $id = null;
    public ?string $label = null;
    public ?string $interpretation = null;
    public ?int $form_question_id = null;
    public ?float $value = null;

    public function rules()
    {
        $uniqueTitle = Rule::unique('form_question_options')
            ->where('label', $this->label)
            ->where('form_question_id', $this->form_question_id);
        return [
            'label'            => ['required', 'max:255', isset($this->id) ? $uniqueTitle->ignore($this->id) : $uniqueTitle],
            'interpretation'   => ['required', 'string', 'max:1025'],
            'form_question_id' => ['required', 'exists:form_questions,id'],
            'value'            => ['required', 'numeric', 'min:0'],
        ];
    }

    //
    public function submit()
    {
        $this->validate();

        if (! isset($this->id)) {
            FormQuestionOption::create([ ...$this->except(['id']), 'order_numerator' => 0]);
        } else {

            FormQuestionOption::whereId($this->id)->update($this->except(['id', 'form_question_id']));
        }

    }

    /**
     * Summary of set
     * @param FormQuestionOption $model
     * @return void
     */
    public function set(mixed $model)
    {
        $this->fill($model->attributesToArray());
    }
}
