<?php
namespace App\Livewire\Forms;

use App\Models\SemesterSection;
use App\Models\StudentSemester;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;

class StudentSemesterForm extends BaseForm
{

    #[Locked]
    public ?int $id;
    #[Locked]
    public ?int $student_id;
    public ?int $semester_id;
    public ?int $section_id;
    public ?int $course_semester_id;
    public ?int $year_level;
    public bool $include_base = true;
    public bool $same_section_for_subjects = true;
    public ?array $course_subject_ids;
    public bool $include_subjects = true;
    public bool $include_section = true;

    public function rules()
    {
        $rules = [];

        if (isset($this->id)) {
            $rules = [
                'id' => ['required', 'integer', 'exists:student_semesters,id'],
            ];
        }

        if ($this->include_base) {
            $uniqueSem = Rule::unique('student_semesters', 'semester_id')
                ->where('student_id', $this->student_id);

            if (isset($this->id)) {
                $uniqueSem = $uniqueSem->ignore($this->id);
            }

            $rules = [ ...$rules,
                'student_id'         => ['required', 'integer', 'exists:students,id'],
                'course_semester_id' => ['required', 'integer', 'exists:course_semesters,id'],
                'semester_id'        => ['required', 'integer', 'exists:semesters,id', $uniqueSem],
            ];
        }

        if ($this->include_section) {
            $rules = [
                 ...$rules,
                'section_id'                => ['required', 'integer', 'exists:sections,id'],
                'same_section_for_subjects' => ['boolean'],
            ];

        }

        if ($this->include_subjects) {
            $uniqueCourseSubject = isset($this->id)
            ? Rule::unique('student_subjects', 'course_subject_id')
                ->where('student_semester_id', $this->id)
            : null;

            $rules = [ ...$rules,
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
            $semSec = SemesterSection::whereSemesterId($this->semester_id)
                ->whereSectionId($this->section_id)
                ->firstOrCreate([
                    'semester_id' => $this->semester_id,
                    'section_id'  => $this->section_id,
                ], ['id']);
            if (isset($this->id)) {
                $model = StudentSemester::whereId($this->id)->first();
                $model->update([
                     ...$this->except([
                        'id',
                        'course_subject_ids',
                        'include_base',
                        'include_subjects',
                        'section_id',
                    ]),
                    'semester_section_id' => $semSec->id,
                ]);
            } else {
                $model = StudentSemester::create([
                     ...$this->except([
                        'id',
                        'course_subject_ids',
                        'include_base',
                        'include_subjects',
                        'section_id',
                    ]),
                    'semester_section_id' => $semSec->id,
                ]);
            }

            $model->studentSubjects()->createMany(
                collect($this->course_subject_ids)->map(fn($id) => [
                    'course_subject_id'   => $id,
                    'semester_section_id' => $this->same_section_for_subjects ? $model->semester_section_id : null,
                ])->toArray()
            );

            if ($this->same_section_for_subjects) {
                $model->studentSubjects()->update([
                    'semester_section_id' => $model->semester_section_id,
                ]);
            }
        });
    }

    /**
     * Summary of set
     * @param StudentSemester $model
     * @return void
     */
    public function set(mixed $model)
    {
        $this->fill([
             ...$model->attributesToArray(),
            'course_subject_ids'        => $model->studentSubjects->pluck('course_subject_id')->toArray(),
            'section_id'                => $model->semesterSection?->section_id,
            'same_section_for_subjects' => false,
        ]);
    }
}
