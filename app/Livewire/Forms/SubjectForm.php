<?php
namespace App\Livewire\Forms;

use App\Models\Subject;
use Livewire\Attributes\Locked;

/**
 * @extends parent<Subject>
 */
class SubjectForm extends BaseForm
{
    #[Locked]
    public ?int $id = null;
    public ?string $code = null;
    public ?string $name = null;

    public function rules()
    {
        $unique = 'unique:subjects,code';
        return [
            'id'   => ['nullable', 'integer', 'exists:subjects,id'],
            'code' => ['required', 'string', 'max:255',
                isset($this->id) ? $unique . ',' . $this->id : $unique],
            'name' => ['required', 'string', 'max:255'],
        ];
    }

    public function submit()
    {
        $this->validate();
        if (isset($this->id)) {
            Subject::whereId($this->id)->update($this->except(['id']));
        } else {
            Subject::create($this->except(['id']));
        }
    }

    /**
     * Summary of set
     * @param Subject $subject
     * @return void
     */
    public function set(mixed $subject)
    {
        $this->fill($subject->attributesToArray());
    }
}
