<?php

namespace App\Livewire\Forms;

use App\Models\CourseSemester;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Form;
use \DB;

class CourseSemesterForm extends Form
{
    #[Locked]
    public ?int $id = null;
    public ?int $course_id = null;
    public ?int $year_level = null;
    public ?int $semester = null;
    public ?array $subject_ids = [];

    public function rules()
    {
        return [
            'id' => ['nullable', 'integer', 'exists:course_semesters,id'],
            'year_level' => ['required', 'integer', 'gt:0'],
            'course_id' => ['required', 'exists:courses,id'],
            'semester' => ['required', 'integer', 'gt:0'],
            'subject_ids' => ['required', 'array', 'min:1'],
            'subject_ids.*' => ['integer', 'exists:subjects,id'],
        ];
    }

    public function save()
    {
        $this->validate();
        $uniqueRule = Rule::unique('course_semesters')
            ->where('course_id', $this->course_id)
            ->where('year_level', $this->year_level)
            ->where('semester', $this->semester)
            ->ignore($this->id ?? 0);

        $this->validate([
            'year_level' => $uniqueRule,
            'semester' => $uniqueRule,
            'course_id' => $uniqueRule,
        ]);

        DB::transaction(function () {
            if (isset($this->id)) {
                $courseSemester = CourseSemester::whereId($this->id)->first();
                $courseSemester->update($this->except(['id', 'subject_ids']));
            } else {
                $courseSemester = CourseSemester::create($this->except(['id', 'subject_ids']));
                $courseSemester->subjects()->sync($this->subject_ids);
            }
        });
    }

    public function set(CourseSemester $courseSemester)
    {
        $this->fill([
             ...$courseSemester->attributesToArray(),
            'subject_ids' => $courseSemester->subjects()->get(['subjects.id'])->pluck('id')->toArray(),
        ]);
    }

    public function clear()
    {
        $this->reset();
        $this->resetErrorBag();
    }
}
