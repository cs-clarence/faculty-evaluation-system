<?php

namespace App\Livewire\Pages\Admin\FormSubmissionPeriods;

use App\Livewire\Forms\FormSubmissionPeriodForm;
use App\Models\Form;
use App\Models\FormSubmissionPeriod;
use App\Models\Semester;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Livewire\Component;

class Index extends Component
{
    public ?FormSubmissionPeriod $model = null;
    public FormSubmissionPeriodForm $form;
    public bool $isFormOpen = false;

    public function render()
    {
        $formSubmissionPeriods = FormSubmissionPeriod::withoutArchived()
            ->with([
                'form',
                'semester' => fn(BelongsTo $buidler) => $buidler->with('schoolYear'),
            ])
            ->orderBy('starts_at')
            ->orderBy('ends_at')
            ->orderBy('form_id')
            ->lazy();

        $exeptFormIds = isset($this->model?->form_id) ? [$this->model->form_id] : [];

        $forms = Form::withoutArchived(exceptIds: $exeptFormIds)
            ->orderBy('name')
            ->lazy();

        $semesters = Semester::with(['schoolYear'])
            ->withCount(['schoolYear'])
            ->orderByDesc('school_year_id')
            ->orderByDesc('semester')
            ->lazy();

        return view('livewire.pages.admin.form-submission-periods.index')
            ->with(compact('formSubmissionPeriods', 'forms', 'semesters'))
            ->layout('components.layouts.admin');
    }

    public function save()
    {
        $this->form->save();
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
        $this->model = $model;
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
