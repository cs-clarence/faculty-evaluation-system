<?php
namespace App\Livewire\Pages\AllRoles;

use App\Facades\Services\FormSubmissionEvaluateeService;
use App\Livewire\Forms\FormSubmissionForm;
use App\Models\CourseSubject;
use App\Models\Form;
use App\Models\FormQuestion;
use App\Models\FormSubmission;
use App\Models\FormSubmissionPeriod;
use App\Models\FormSubmissionSubject;
use App\Models\User;
use Auth;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\Component;

class SubmitEvaluation extends Component
{

    public FormSubmissionPeriod $formSubmissionPeriod;
    #[Locked]
    public ?User $evaluatee = null;

    #[Url]
    public ?int $evaluateeId = null;
    #[Url]
    public ?int $courseSubjectId = null;
    #[Url]
    public ?int $studentSubjectId = null;
    #[Url]
    public ?int $formSubmissionId = null;
    #[Locked]
    public ?FormSubmission $formSubmission = null;
    public ?CourseSubject $courseSubject = null;
    public FormSubmissionForm $form;
    public $previousRoute;

    public function getCreateWireModel()
    {
        return fn(FormQuestion $formQuestion) => 'form.questions.' . $formQuestion->id;
    }

    public function mount()
    {
        $this->previousRoute = url()->previous();
        if (isset($this->formSubmissionId)) {
            $fs                   = FormSubmission::whereId($this->formSubmissionId)->first();
            $this->formSubmission = $fs;
            $this->courseSubject  = $fs->courseSubject;
            $this->evaluatee      = $fs->evaluatee;
            $this->form->set($fs);
        }

        if (isset($this->studentSubjectId)) {
            $submission = FormSubmissionSubject::whereStudentSubjectId($this->studentSubjectId)->first()?->formSubmission;
            if (isset($submission)) {
                $this->evaluatee = $submission->evaluate;
                $this->form->id  = $submission->id;
            }
        }

        if (isset($this->evaluateeId)) {
            $this->evaluatee = User::whereId($this->evaluateeId)->first();
        }
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

        $users = isset($this->evaluatee) ? [] : FormSubmissionEvaluateeService::getAvailableEvaluatees(
            $this->formSubmissionPeriod,
            \Illuminate\Support\Facades\Auth::user(),
            $this->courseSubject ?? $this->courseSubjectId
        )->lazy();

        return view('livewire.pages.all-roles.submit-evaluation')
            ->with(compact('formModel', 'users'))
            ->layout('components.layouts.user');
    }

    public function save()
    {
        $this->form->evaluator_id              = Auth::user()->id;
        $this->form->student_subject_id        = $this->studentSubjectId;
        $this->form->course_subject_id         = $this->courseSubjectId;
        $this->form->form_submission_period_id = $this->formSubmissionPeriod->id;
        if (isset($this->evaluateeId) || isset($this->evaluatee)) {
            $this->form->evaluatee_id = $this->evaluatee?->id ?? $this->evaluateeId;
        }
        $this->form->submit();
        Session::flash('success', 'Form submitted succcessfully.');
        return $this->redirect($this->previousRoute);
    }
}
