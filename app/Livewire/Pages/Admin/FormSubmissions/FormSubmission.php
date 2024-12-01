<?php

namespace App\Livewire\Pages\Admin\FormSubmissions;

use App\Livewire\Forms\FormSubmissionForm;
use App\Models\Form;
use App\Models\FormQuestion;
use App\Models\FormSubmission as Model;
use Livewire\Component;

class FormSubmission extends Component
{

    public Model $formSubmission;
    public Form $formModel;
    public FormSubmissionForm $form;

    public function getCreateWireModel()
    {
        return fn(FormQuestion $formQuestion) => 'form.questions.' . $formQuestion->id;
    }
    public function mount()
    {
        $this->formSubmission->load(['submissionPeriod.semester', 'studentSubject']);
        $this->form->set($this->formSubmission);
        $this->formModel = Form::with(['sections.questions.options'])
            ->whereId($this->formSubmission->form_id)
            ->first();
    }

    public function render()
    {
        return view('livewire.pages.admin.form-submissions.form-submission')
            ->layout('components.layouts.admin');

    }
}
