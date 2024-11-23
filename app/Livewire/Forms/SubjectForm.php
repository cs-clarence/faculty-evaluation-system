<?php

namespace App\Livewire\Forms;

use App\Models\Subject;
use Livewire\Attributes\Locked;
use Livewire\Form;

class SubjectForm extends Form
{
    public bool $isOpen = false;
    #[Locked]
    public ?int $subjectId = null;
    public ?string $code = null;
    public ?string $name = null;

    public function rules()
    {
        $unique = 'unique:subjects,code';
        return [
            'subjectId' => ['nullable', 'integer', 'exists:subjects,id'],
            'code' => ['required', 'string', 'max:255',
                isset($this->subjectId) ? $unique . ',' . $this->subjectId : $unique],
            'name' => ['required', 'string', 'max:255'],
        ];
    }

    public function save()
    {
        $this->validate();
        if (isset($this->subjectId)) {
            Subject::whereId($this->subjectId)->update($this->except(['subjectId', 'isOpen']));
        } else {
            Subject::create($this->except(['isOpen', 'subjectId']));
        }
        $this->close();
    }

    public function open(?Subject $subject = null)
    {
        if (isset($subject)) {
            $this->set($subject);
        }
        $this->isOpen = true;
    }

    public function set(Subject $subject)
    {
        $this->fill(
            [
                'subjectId' => $subject->id,
                'code' => $subject->code,
                'name' => $subject->name,
            ]
        );
    }

    public function close()
    {
        $this->isOpen = false;
        $this->reset();
        $this->resetErrorBag();
    }
}
