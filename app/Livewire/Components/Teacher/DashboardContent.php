<?php
namespace App\Livewire\Components\Teacher;

use App\Models\FormSubmission;
use Livewire\Component;

class DashboardContent extends Component
{
    public function render()
    {
        $formSubmissions = FormSubmission::with([
            'studentSubject' => [
                'courseSubject.subject',
            ],
            'submissionPeriod.semester.schoolYear',
        ])->lazy();
        return view('livewire.components.teacher.dashboard-content')
            ->with(compact('formSubmissions'))
            ->layout('components.layouts.user');
    }
}
