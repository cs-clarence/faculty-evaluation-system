<?php
namespace App\Livewire\Pages\Admin\FormSubmissionPeriods;

use App\Livewire\Forms\FormSubmissionPeriodForm;
use App\Livewire\Traits\WithSearch;
use App\Models\Form;
use App\Models\FormSubmissionPeriod;
use App\Models\Role;
use App\Models\Semester;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination, WithSearch;
    public ?FormSubmissionPeriod $model = null;
    public FormSubmissionPeriodForm $form;
    public bool $isFormOpen   = false;
    public bool $showSemester = false;

    public function render()
    {
        $formSubmissionPeriods = FormSubmissionPeriod::with([
            'form',
            'semester' => fn(BelongsTo $buidler) => $buidler->with('schoolYear'),
        ])
            ->orderBy('starts_at')
            ->orderBy('ends_at')
            ->orderBy('form_id');

        if ($this->shouldSearch()) {
            $formSubmissionPeriods = $formSubmissionPeriods->fullTextSearch([
                'columns'   => ['name'],
                'relations' => [
                    'form'          => [
                        'columns' => ['name', 'description'],
                    ],
                    'evaluateeRole' => [
                        'columns' => ['display_name', 'code'],
                    ],
                    'evaluatorRole' => [
                        'columns' => ['display_name', 'code'],
                    ],
                ],
            ], $this->searchText);
        }

        $formSubmissionPeriods = $formSubmissionPeriods->cursorPaginate(15);

        $exeptFormIds = isset($this->model?->form_id) ? [$this->model->form_id] : [];

        $forms = Form::withoutArchived(exceptIds: $exeptFormIds)
            ->orderBy('name')
            ->lazy();

        $semesters = Semester::with(['schoolYear'])
            ->whereHas('schoolYear', fn(Builder $schoolYear) => $schoolYear->active())
            ->withCount(['schoolYear'])
            ->orderByDesc('school_year_id')
            ->orderByDesc('semester')
            ->lazy();

        $evaluatorRoles = Role::canBeEvaluator()
            ->lazy();

        $evaluateeRoles = Role::canBeEvaluatee()
            ->lazy();

        $this->showSemester = $this->form->shouldRequireSemester();

        return view('livewire.pages.admin.form-submission-periods.index')
            ->with(compact('formSubmissionPeriods', 'forms', 'semesters', 'evaluatorRoles', 'evaluateeRoles'))
            ->layout('components.layouts.admin');
    }

    public function updated(string $name, $value)
    {
        if ($name === "form.evaluator_role_id" || $name = "form.evaluatee_role_id") {
            $this->showSemester = $this->form->shouldRequireSemester();
        }
    }

    public function save()
    {
        $this->form->submit();
        $this->closeForm();
    }

    public function openForm()
    {
        $this->isFormOpen = true;
    }

    public function closeForm()
    {
        $this->isFormOpen = false;
        $this->form->clear();
        $this->model = null;
    }

    public function delete(FormSubmissionPeriod $model)
    {
        $model->delete();
    }

    public function edit(FormSubmissionPeriod $model)
    {
        $this->model      = $model;
        $this->isFormOpen = true;
        $this->form->set($model);
    }

    public function archive(FormSubmissionPeriod $model)
    {
        $model->archive();
    }

    public function unarchive(FormSubmissionPeriod $model)
    {
        $model->unarchive();
    }

    public function open(FormSubmissionPeriod $model)
    {
        $model->open();
    }

    public function close(FormSubmissionPeriod $model)
    {
        $model->close();
    }
}
