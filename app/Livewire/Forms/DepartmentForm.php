<?php

namespace App\Livewire\Forms;

use App\Models\Department;
use Livewire\Attributes\Locked;
use Livewire\Form;

class DepartmentForm extends Form
{
    public bool $isOpen = false;
    #[Locked]
    public ?int $departmentId = null;
    public ?string $code = null;
    public ?string $name = null;

    public function rules()
    {
        $unique = 'unique:departments,code';
        return [
            'departmentId' => ['nullable', 'integer', 'exists:departments,id'],
            'code' => ['required', 'string', 'max:255',
                isset($this->departmentId) ? $unique . ',' . $this->departmentId : $unique],
            'name' => ['required', 'string', 'max:255'],
        ];
    }

    public function save()
    {
        $this->validate();
        if (isset($this->departmentId)) {
            Department::whereId($this->departmentId)->update($this->except(['departmentId', 'isOpen']));
        } else {
            Department::create($this->except(['isOpen', 'departmentId']));
        }
        $this->close();
    }

    public function open(?Department $department = null)
    {
        if (isset($department)) {
            $this->set($department);
        }
        $this->isOpen = true;
    }

    public function set(Department $department)
    {
        $this->fill(
            [
                'departmentId' => $department->id,
                'code' => $department->code,
                'name' => $department->name,
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
