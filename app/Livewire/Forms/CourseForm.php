<?php

namespace App\Livewire\Forms;

use App\Models\Course;
use Livewire\Attributes\Locked;
use Livewire\Form;

class CourseForm extends Form
{
    #[Locked]
    public ?int $id = null;
    public ?int $department_id = null;
    public ?string $code = null;
    public ?string $name = null;

    public function rules()
    {
        $unique = 'unique:courses,code';
        return [
            'id' => ['nullable', 'integer', 'exists:courses,id'],
            'code' => ['required', 'string', 'max:255',
                isset($this->id) ? $unique . ',' . $this->id : $unique],
            'name' => ['required', 'string', 'max:255'],
            'department_id' => ['required', 'integer', 'exists:departments,id'],
        ];
    }

    public function save()
    {
        $this->validate();
        if (isset($this->id)) {
            Course::whereId($this->id)->update($this->except(['id', 'isOpen']));
        } else {
            Course::create($this->except(['id', 'isOpen']));
        }
    }

    public function set(Course $course)
    {
        $this->fill($course->attributesToArray());
    }

    public function clear()
    {
        $this->reset();
        $this->resetErrorBag();
    }
}
