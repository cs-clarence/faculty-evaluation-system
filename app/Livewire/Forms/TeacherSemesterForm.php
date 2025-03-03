<?php
namespace App\Livewire\Forms;

use App\Models\TeacherSemester;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;

class TeacherSemesterForm extends BaseForm
{
    #[Locked]
    public ?int $id;
    public ?int $teacher_id;
    public ?int $semester_id;
    public bool $include_base = true;
    public ?int $course_id;
    public array $course_subject_ids = [];
    public bool $include_subjects = true;

    public function rules()
    {
        $rules = [];

        if (isset($this->id)) {
            $rules = [
                'id' => ['required', 'integer', 'exists:teacher_semesters,id'],
            ];
        }

        if ($this->include_base) {
            $rules = [ ...$rules,
                'teacher_id'  => ['required', 'integer', 'exists:teachers,id'],
                'semester_id' => ['required', 'integer', 'exists:semesters,id'],
            ];
        }

        if ($this->include_subjects) {
            $rules = [ ...$rules,
                'course_id'            => ['required', 'integer', 'exists:courses,id'],
                'course_subject_ids'   => ['required', 'array', 'min:1'],
                'course_subject_ids.*' => ['required', 'exists:course_subjects,id'],
            ];
        }

        return $rules;
    }

    public function submit()
    {
        DB::transaction(function () {
            if (isset($this->id)) {
            } else {
            }
        });
    }

    /**
     * Summary of set
     * @param TeacherSemester $model
     * @return void
     */
    public function set(mixed $model)
    {
        if (! isset($model->teacherSubjects) || $model->teacherSubjects->isEmpty()) {
            $model->load(['teacherSubjects.courseSubject']);
        }
        $this->fill([
             ...$model->attributesToArray(),
            'course_subject_ids' => $model->teacherSubjects->pluck('course_subject_id'),
        ]);
    }
}
