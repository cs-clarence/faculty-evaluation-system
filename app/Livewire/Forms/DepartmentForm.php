<?php
namespace App\Livewire\Forms;

use App\Models\Department;
use Livewire\Attributes\Locked;

/**
 * @extends parent<Department>
 */

class DepartmentForm extends BaseForm
{
    #[Locked]
    public ?int $id = null;
    public ?string $code = null;
    public ?string $name = null;

    public function rules()
    {
        $unique = 'unique:departments,code';
        return [
            'id'   => ['nullable', 'integer', 'exists:departments,id'],
            'code' => ['required', 'string', 'max:255',
                isset($this->id) ? $unique . ',' . $this->id : $unique],
            'name' => ['required', 'string', 'max:255'],
        ];
    }

    public function submit()
    {
        $this->validate();
        if (isset($this->id)) {
            Department::whereId($this->id)->update($this->except(['id']));
        } else {
            Department::create($this->except(['id']));
        }
        $this->clear();
    }

    /**
     * @param Department $department
     */

    public function set(mixed $department)
    {
        $this->fill($department->attributesToArray());
    }
}
