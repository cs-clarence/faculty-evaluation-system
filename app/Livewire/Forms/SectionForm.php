<?php

namespace App\Livewire\Forms;

use App\Models\Section;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;

class SectionForm extends BaseForm
{
    #[Locked]
    public ?int $id = null;
    public ?int $year_level = null;
    public ?int $semester = null;
    public ?string $code = null;
    public ?string $name = null;
    public ?int $course_id = null;

    public function rules()
    {
        $unique = 'unique:sections,code';
        return [
            'id' => ['nullable', 'integer', 'exists:subjects,id'],
            'code' => ['required', 'string', 'max:255',
                isset($this->id) ? $unique . ',' . $this->id : $unique],
            'year_level' => ['required', 'integer', 'gt:0'],
            'semester' => ['required', 'integer', 'gt:0'],
            'name' => ['required', 'string', 'max:255'],
            'course_id' => ['required', 'integer', 'exists:courses,id'],
        ];
    }

    public function submit()
    {
        $this->validate();

        $this->validate([
            'name' => Rule::unique('sections')
                ->where('course_id', $this->course_id)
                ->where('year_level', $this->year_level)
                ->where('semester', $this->semester)
                ->where('name', $this->name)
                ->ignore($this->id ?? 0),
        ]);

        if (isset($this->id)) {
            Section::whereId($this->id)->update($this->except(['id']));
        } else {
            Section::create($this->except(['id']));
        }
    }

    public function set(Section $section)
    {
        $this->fill($section->attributesToArray());
    }
}
