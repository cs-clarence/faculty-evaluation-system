<?php

namespace App\Livewire\Forms;

use App\Models\Subject;
use Livewire\Attributes\Locked;
use Livewire\Form;

class SubjectForm extends Form
{
    #[Locked]
    public ?int $id = null;
    public ?string $code = null;
    public ?string $name = null;

    public function rules()
    {
        $unique = 'unique:subjects,code';
        return [
            'id' => ['nullable', 'integer', 'exists:subjects,id'],
            'code' => ['required', 'string', 'max:255',
                isset($this->id) ? $unique . ',' . $this->id : $unique],
            'name' => ['required', 'string', 'max:255'],
        ];
    }

    public function save()
    {
        $this->validate();
        if (isset($this->id)) {
            Subject::whereId($this->id)->update($this->except(['id']));
        } else {
            Subject::create($this->except(['id']));
        }
    }

    public function set(Subject $subject)
    {
        $this->fill($subject->attributesToArray());
    }

    public function clear()
    {
        $this->reset();
        $this->resetErrorBag();
    }
}
