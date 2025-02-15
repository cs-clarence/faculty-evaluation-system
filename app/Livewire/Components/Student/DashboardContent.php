<?php
namespace App\Livewire\Components\Student;

use App\Facades\Services\StudentService;
use App\Models\FormSubmissionPeriod;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Livewire\Component;

class DashboardContent extends Component
{
    public function render()
    {
        $activePeriods = FormSubmissionPeriod::with(['semester.schoolYear'])->isOpen()->get();

        $activePeriodSemesterIds = $activePeriods->map(fn($i) => $i->semester_id);

        $student = StudentService::currentStudent()->with([
            'studentSubjects' => fn(HasManyThrough $studentSubjects) => $studentSubjects
                ->with([
                    'courseSubject' => [
                        'subject',
                        'courseSemester.course.department',
                    ],
                    'studentSemester',
                    'teacherSubject.teacherSemester.teacher',
                ])
                ->whereIn('semester_id', $activePeriodSemesterIds),
            'user',
        ])->first();

        $submissionPeriods = [];

        foreach ($activePeriods as $p) {
            $studentSubjects = $student->studentSubjects->filter(fn($ss) =>
                $ss->studentSemester->semester_id === $p->semester_id
            );
            $o                       = new \stdClass;
            $o->formSubmissionPeriod = $p;
            $o->studentSubjects      = $studentSubjects;

            if (count($o->studentSubjects) > 0) {
                $submissionPeriods[] = $o;
            }
        }

        return view('livewire.components.student.dashboard-content')
            ->with(compact('student', 'submissionPeriods'))
            ->layout('components.layouts.user');
    }
}
