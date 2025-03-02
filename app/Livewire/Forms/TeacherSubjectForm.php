<?php
namespace App\Livewire\Forms;

use App\Models\SemesterSection;
use App\Models\TeacherSemester;
use App\Models\TeacherSubject;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;

class TeacherSubjectForm extends BaseForm
{
    #[Locked]
    public ?int $id;
    public ?array $section_ids;
    public ?int $teacher_semester_id;
    public ?int $course_subject_id;

    public function rules()
    {
        return [
            'id'                  => ['nullable', 'integer', 'exists:teacher_subjects,id'],
            'section_ids'         => ['required', 'array'],
            'section_ids.*'       => ['required', 'integer', 'exists:sections,id'],
            'teacher_semester_id' => ['required', 'integer', 'exists:teacher_semesters,id'],
            'course_subject_id'   => ['required', 'integer', 'exists:course_subjects,id'],
        ];
    }

    public function submit()
    {
        $this->validate();

        DB::transaction(function () {
            if (isset($this->id)) {
                $model = TeacherSubject::whereId($this->id)->first();
                $model->update($this->except(['id', 'section_ids']));
            } else {
                $model = TeacherSubject::create($this->except(['id', 'section_ids']));
            }

            $semesterId = TeacherSemester::whereId($this->teacher_semester_id)
                ->first(['semester_id'])
                ->semester_id;

            $semesterSectionIds = [];

            foreach ($this->section_ids as $sectionId) {
                $semSec = SemesterSection::whereSemesterId($semesterId)
                    ->whereSectionId($sectionId)
                    ->firstOrCreate([
                        'semester_id' => $semesterId,
                        'section_id'  => $sectionId,
                    ]);

                $semesterSectionIds[] = $semSec->id;
            }

            $model->semesterSections()->sync($semesterSectionIds);
        });

    }

    /**
     * Summary of set
     * @param \App\Models\TeacherSubject $model
     * @return void
     */
    public function set(mixed $model)
    {
        $this->fill([
             ...$model->attributesToArray(),
            'section_ids' => $model->semesterSections->pluck('section_id')->toArray(),
        ]);
    }
}
