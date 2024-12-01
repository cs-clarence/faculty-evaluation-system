<?php

namespace App\Livewire\Forms;

use App\Models\Form as FormModel;
use Livewire\Attributes\Locked;

class FormForm extends BaseForm
{
    #[Locked]
    public ?int $id = null;
    public ?string $name = null;
    public ?string $description = null;

    public function rules()
    {
        return [
            'id' => ['nullable', 'integer', 'exists:forms,id'],
            'name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
        ];
    }

    public function submit()
    {
        $this->validate();
        if (isset($this->id)) {
            FormModel::whereId($this->id)->update($this->except(['id']));
        } else {
            FormModel::create($this->except(['id']));
        }
    }

    public function set(FormModel $model)
    {
        $this->fill([ ...$model->attributesToArray()]);
    }
}
