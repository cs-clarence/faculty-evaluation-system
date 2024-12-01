<?php

namespace App\Livewire\Pages\Student\FormSubmission;

use App\Models\FormSubmissionPeriod;
use App\Models\StudentSubject;
use App\Models\Teacher;
use Livewire\Component;

class Index extends Component
{

    public FormSubmissionPeriod $formSubmissionPeriod;
    public StudentSubject $studentSubject;
    public ?Teacher $teacher;

    public function render()
    {
        return view('livewire.pages.student.form-submission.index')
            ->layout('components.layouts.student');
    }
}
