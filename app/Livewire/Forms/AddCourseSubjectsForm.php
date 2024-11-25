<?php

namespace App\Livewire\Forms;

use App\Models\CourseSemester;
use Livewire\Attributes\Locked;
use Livewire\Form;

class AddCourseSubjectsForm extends Form
{
    #[Locked]
    public ?int $course_semester_id = null;
    public ?array $subject_ids = [];

    public function rules()
    {
        return [
            'course_semester_id' => ['nullable', 'integer', 'exists:course_semesters,id'],
            'subject_ids' => ['required', 'array', 'min:1'],
            'subject_ids.*' => ['integer', 'exists:subjects,id'],
        ];
    }

    public function save()
    {
        $this->validate();

        $courseSemester = CourseSemester::whereId($this->course_semester_id)->first();

        $courseSemester->subjects()->syncWithoutDetaching($this->subject_ids);
    }

    public function set(CourseSemester $courseSemester)
    {
        $this->fill([
            'course_semester_id' => $courseSemester->id,
            'subject_ids' => [],
        ]);
    }

    public function clear()
    {
        $this->reset();
        $this->resetErrorBag();
    }
}
