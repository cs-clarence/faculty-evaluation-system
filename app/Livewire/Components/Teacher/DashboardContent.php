<?php
namespace App\Livewire\Components\Teacher;

use App\Models\FormSubmission;
use Livewire\Component;

class DashboardContent extends Component
{
    public function render()
    {
        $formSubmissions = FormSubmission::with([
            'submissionPeriod.formSubmissionPeriodSemester.semester.schoolYear',
        ])->whereEvaluateeId(auth()->user()->id);

        $formSubmissions = $formSubmissions->cursorPaginate(15);
        return view('livewire.components.teacher.dashboard-content')
            ->with(compact('formSubmissions'))
            ->layout('components.layouts.user');
    }
}
