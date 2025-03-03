<?php
namespace App\Livewire\Pages\Admin\Students;

use App\Models\Student as Model;
use App\Models\StudentSemester;
use App\Models\StudentSubject;
use Livewire\Component;

class Student extends Component
{
    public Model $student;

    public function mount()
    {
        $this->student->loadCount(['studentSemesters', 'studentSubjects']);
        $this->student->load([
            'studentSemesters' => [
                'studentSubjects' => ['courseSubject.subject', 'semesterSection.section'],
                'semesterSection.section',
                'semester',
            ],
        ]);
    }

    public function render()
    {
        return view('livewire.pages.admin.students.student')
            ->layout('components.layouts.admin');
    }

    public function archiveStudentSubject(StudentSubject $model)
    {
        $model->archive();
    }

    public function unarchiveStudentSubject(StudentSubject $model)
    {
        $model->unarchive();
    }

    public function deleteStudentSubject(StudentSubject $model)
    {
        $model->delete();
    }

    public function deleteStudentSemester(StudentSemester $model)
    {
        $model->delete();
    }
}
