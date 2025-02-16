<?php
namespace App\Livewire\Forms;

use App\Models\FormSection;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;

/**
 * @extends parent<FormSection>
 */
class FormSectionForm extends BaseForm
{
    #[Locked]
    public ?int $id = null;
    #[Locked]
    public ?int $form_id = null;
    public ?string $title = null;
    public ?string $description = null;

    public function rules()
    {
        $unique = Rule::unique('form_sections', 'title')
            ->where('form_id', $this->form_id);
        return [
            'title'       => [isset($this->id) ? $unique->ignore($this->id) : $unique, 'max:255', 'min:1'],
            'description' => ['nullable', 'string', 'max:1025'],
        ];
    }

    //
    public function submit()
    {
        $this->validate();
        if (! isset($this->id)) {
            FormSection::create([ ...$this->except(['id']), 'order_numerator' => 0]);
        } else {
            FormSection::whereId($this->id)->update([ ...$this->except(['id', 'form_id']), 'order_numerator' => 0]);
        }
    }

    /**
     * Summary of set
     * @param FormSection $model
     * @return void
     */
    public function set(mixed $model)
    {
        $this->fill($model->attributesToArray());
    }
}
