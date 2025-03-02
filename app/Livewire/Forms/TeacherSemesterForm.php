<?php
namespace App\Livewire\Forms;

use App\Models\Course;
use App\Models\TeacherSemester;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;

class TeacherSemesterForm extends BaseForm
{
    #[Locked]
    public ?int $id;
    #[Locked]
    public ?int $teacher_id;
    public ?int $semester_id;
    public bool $include_base = true;
    public ?array $course_ids;
    public ?array $course_subject_ids;
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
            $uniqueSem = Rule::unique('teacher_semesters', 'semester_id')
                ->where('teacher_id', $this->teacher_id);

            if (isset($this->id)) {
                $uniqueSem = $uniqueSem->ignore($this->id);
            }

            $rules = [ ...$rules,
                'teacher_id'  => ['required', 'integer', 'exists:teachers,id'],
                'semester_id' => ['required', 'integer', 'exists:semesters,id', $uniqueSem],
            ];
        }

        if ($this->include_subjects) {
            $uniqueCourseSubject = isset($this->id)
            ? Rule::unique('teacher_subjects', 'course_subject_id')
                ->where('teacher_semester_id', $this->id)
            : null;

            $rules = [ ...$rules,
                'course_ids'           => ['required', 'array', 'min:1'],
                'course_ids.*'         => ['required', 'integer', 'exists:courses,id'],
                'course_subject_ids'   => ['required', 'array', 'min:1'],
                'course_subject_ids.*' => ['required', 'exists:course_subjects,id', $uniqueCourseSubject],
            ];
        }

        return $rules;
    }

    public function submit()
    {
        $this->validate();
        DB::transaction(function () {
            if (isset($this->id)) {
                $sem = TeacherSemester::whereId($this->id)->first();
                $sem->update([
                    'teacher_id'  => $this->teacher_id,
                    'semester_id' => $this->semester_id,
                ]);
            } else {
                $sem = TeacherSemester::create([
                    'teacher_id'  => $this->teacher_id,
                    'semester_id' => $this->semester_id,
                ]);
            }

            $arr = collect($this->course_subject_ids)->map(fn($data) => [
                'course_subject_id' => $data,
            ])->toArray();
            $sem->teacherSubjects()->createMany($arr);
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
        $courseSubjectIds = $model->teacherSubjects->pluck('course_subject_id')->toArray();
        $this->fill([
             ...$model->attributesToArray(),
            'course_subject_ids' => $courseSubjectIds,
            'course_ids'         => Course::whereHas('courseSubjects', fn($query) => $query->whereIn('course_subjects.id', $courseSubjectIds))->get(['courses.id'])->pluck('id')->toArray(),
        ]);
    }
}
