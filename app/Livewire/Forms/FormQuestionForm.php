<?php
namespace App\Livewire\Forms;

use App\Models\FormQuestion;
use App\Models\FormQuestionType;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Form;

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
    public ?int $form_section_id = null;

    public function rules()
    {
        $uniqueTitle = Rule::unique('form_questions')
            ->where('form_section_id', $this->form_id)
            ->where('title', $this->title);

        return [
            'title'           => ['required', 'min:0', 'max:255', isset($this->id) ? $uniqueTitle->ignore($this->id) : $uniqueTitle],
            'description'     => ['nullable', 'max:1025'],
            'type'            => ['required', Rule::enum(FormQuestionType::class)],
            'form_id'         => ['required', 'exists:forms,id'],
            'form_section_id' => ['required', 'exists:form_sections,id'],
        ];
    }

    public function submit()
    {
        $this->validate();

        if (! isset($this->id)) {
            FormQuestion::create([ ...$this->except(['id']), 'order_numerator' => 0]);
        } else {
            FormQuestion::whereId($this->id)->update($this->except(['id', 'form_id', 'form_section_id']));
        }

    }

    /**
     * @param FormQuestion $model
     **/
    public function set(mixed $model)
    {
        $this->fill($model->attributesToArray());
    }
}
