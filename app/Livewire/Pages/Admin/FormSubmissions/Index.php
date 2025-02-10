<?php
namespace App\Livewire\Pages\Admin\FormSubmissions;

use App\Models\FormSubmission;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $formSubmissions = FormSubmission::with([
            'studentSubject.studentSemester' => ['student.user', 'semester'],
            'teacher.user',
        ]);
        return view('livewire.pages.admin.form-submissions.index')
            ->with(compact('formSubmissions'))
            ->layout('components.layouts.admin');
    }
}
