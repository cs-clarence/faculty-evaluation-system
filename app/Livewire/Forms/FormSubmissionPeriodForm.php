<?php

namespace App\Livewire\Forms;

use App\Models\FormSubmissionPeriod;
use Carbon\Carbon;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Form;

class FormSubmissionPeriodForm extends Form
{
    #[Locked]
    public ?int $id = null;
    public ?string $name = null;
    public ?string $starts_at = null;
    public ?string $ends_at = null;
    public bool $is_open = false;
    public bool $is_submissions_editable = false;
    public ?int $form_id = null;
    public ?int $semester_id = null;

    public function rules()
    {
        return [
            'id' => ['nullable', 'integer', 'exists:form_submission_periods,id'],
            'form_id' => ['required', 'integer', 'exists:forms,id'],
            'semester_id' => ['required', 'exists:semesters,id'],
            'name' => ['required', 'string', 'unique:form_submission_periods,name' .
                (isset($this->id) ? ",$this->id" : ''),
            ],
            'starts_at' => ['required', 'date', 'before:ends_at'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
            'is_open' => ['required', 'boolean'],
            'is_submissions_editable' => ['required', 'boolean'],
        ];
    }

    public function save()
    {
        $this->validate();
        if (isset($this->id)) {
            FormSubmissionPeriod::whereId($this->id)->update($this->except(['id']));
        } else {
            FormSubmissionPeriod::create($this->except(['id']));
        }
    }

    public function set(FormSubmissionPeriod $model)
    {
        $this->fill([ ...$model->attributesToArray(),
            'starts_at' => Carbon::make($model->starts_at)->format('Y-m-d\TH:i'),
            'ends_at' => Carbon::make($model->ends_at)->format('Y-m-d\TH:i'),
        ]);
    }

    public function clear()
    {
        $this->reset();
        $this->resetErrorBag();
    }
}
