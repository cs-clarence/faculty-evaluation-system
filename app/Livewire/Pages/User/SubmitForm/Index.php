<?php
namespace App\Livewire\Pages\User\SubmitForm;

use App\Livewire\Forms\FormSubmissionForm;
use App\Models\Form;
use App\Models\FormQuestion;
use App\Models\FormSubmissionPeriod;
use App\Models\StudentSubject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Index extends Component
{

    public FormSubmissionPeriod $formSubmissionPeriod;
    public StudentSubject $studentSubject;
    public ?User $evaluatee = null;
    public FormSubmissionForm $form;

    public function getCreateWireModel()
    {
        return fn(FormQuestion $formQuestion) => 'form.questions.' . $formQuestion->id;
    }

    public function mount()
    {
        $this->form->form_submission_period_id = $this->formSubmissionPeriod->id;
        $this->form->student_subject_id        = $this->studentSubject->id;
        $this->form->evaluatee_id              = $this->evaluatee?->id;
        $this->form->form_id                   = $this->formSubmissionPeriod->form_id;
    }

    public function render()
    {
        $formModel = Form::with([
            'sections' => fn(HasMany $sections) => $sections->with([
                'questions' => fn(HasMany $questions) => $questions->with([
                    'options' => fn(HasMany $options) => $options->orderByDesc('value'),
                ]),
            ]),
        ])->whereId($this->formSubmissionPeriod->form_id)
            ->first();

        $teachers = isset($this->evaluatee) ? [] : Teacher::with(['user'])
            ->where('department_id', $this->studentSubject->courseSubject->courseSemester->course->department_id)
            ->lazy();

        return view('livewire.pages.user.submit-form.index')
            ->with(compact('formModel', 'teachers'))
            ->layout('components.layouts.student');
    }

    public function save()
    {
        $this->form->submit();
        Session::flash('success', 'Form submitted succcessfully.');
        return $this->redirectRoute('student.dashboard.index');
    }
}
