<?php
namespace App\Livewire\Forms;

use App\Models\SemesterSection;
use App\Models\StudentSemester;
use App\Models\StudentSubject;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;

class StudentSubjectForm extends BaseForm
{
    #[Locked]
    public ?int $id;
    public ?int $section_id;
    public ?int $student_semester_id;
    public ?int $course_subject_id;

    public function rules()
    {
        return [
            'id'                  => ['nullable', 'integer', 'exists:student_subjects,id'],
            'section_id'          => ['required', 'integer', 'exists:sections,id'],
            'student_semester_id' => ['required', 'integer', 'exists:student_semesters,id'],
            'course_subject_id'   => ['required', 'integer', 'exists:course_subjects,id'],
        ];
    }

    public function submit()
    {
        $this->validate();

        DB::transaction(function () {
            $semesterId = StudentSemester::whereId($this->student_semester_id)
                ->first(['semester_id'])
                ->semester_id;

            $semSec = SemesterSection::whereSemesterId($semesterId)
                ->whereSectionId($this->section_id)
                ->firstOrCreate([
                    'semester_id' => $semesterId,
                    'section_id'  => $this->section_id,
                ], ['id']);

            if (isset($this->id)) {
                $model = StudentSubject::whereId($this->id)->first();
                $model->update([
                     ...$this->except(['id', 'section_id']),
                    'semester_section_id' => $semSec->id,
                ]);
            } else {
                $model = StudentSubject::create([
                     ...$this->except(['id', 'section_id']),
                    'semester_section_id' => $semSec->id,
                ]);
            }
        });

    }

    /**
     * Summary of set
     * @param \App\Models\StudentSubject $model
     * @return void
     */
    public function set(mixed $model)
    {
        $this->fill([
             ...$model->attributesToArray(),
            'section_id' => $model->semesterSection?->section_id,
        ]);
    }
}
