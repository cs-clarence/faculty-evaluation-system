<?php

namespace App\Livewire\Pages\Teacher\Dashboard;

use App\Models\FormSubmission;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $formSubmissions = FormSubmission::with([
            'studentSubject' => [
                'courseSubject.subject',
            ],
            'submissionPeriod.semester.schoolYear',
        ])->lazy();
        return view('livewire.pages.teacher.dashboard.index')
            ->with(compact('formSubmissions'))
            ->layout('components.layouts.teacher');
    }
}
