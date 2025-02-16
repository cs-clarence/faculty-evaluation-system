<?php
namespace App\Livewire\Forms;

use App\Models\FormSubmissionPeriod;
use App\Models\Role;
use App\Models\RoleCode;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;

/**
 * @extends parent<FormSubmissionPeriod>
 */
class FormSubmissionPeriodForm extends BaseForm
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
    public ?int $evaluator_role_id = null;
    public ?int $evaluatee_role_id = null;

    public function rules()
    {
        $semRules = [
            'exists:semesters,id',
            Rule::unique(FormSubmissionPeriod::class)
                ->where('form_id', $this->form_id)
                ->where('semester_id', $this->semester_id)
                ->ignore($this->id, 'id'),

        ];
        return [
            'id'                      => ['nullable', 'integer', 'exists:form_submission_periods,id'],
            'form_id'                 => ['required', 'integer', 'exists:forms,id'],
            'evaluator_role_id'       => ['required', 'exists:roles,id'],
            'evaluatee_role_id'       => ['required', 'exists:roles,id'],
            'semester_id'             => $this->shouldRequireSemester() ? ['required', ...$semRules] : [],
            'name'                    => ['required', 'string', 'unique:form_submission_periods,name' .
                (isset($this->id) ? ",$this->id" : ''),
            ],
            'starts_at'               => ['required', 'date', 'before:ends_at'],
            'ends_at'                 => ['required', 'date', 'after:starts_at'],
            'is_open'                 => ['required', 'boolean'],
            'is_submissions_editable' => ['required', 'boolean'],
        ];
    }

    public function submit()
    {
        $this->validate();
        if (isset($this->id)) {
            FormSubmissionPeriod::whereId($this->id)->update($this->except(['id']));
        } else {
            FormSubmissionPeriod::create($this->except(
                $this->shouldRequireSemester()
                ? ['id']
                : ['id', 'semester_id']
            ));
        }
    }

    /**
     * @param FormSubmissionPeriod $model
     */

    public function set(mixed $model)
    {
        $this->fill([ ...$model->attributesToArray(),
            'starts_at' => Carbon::make($model->starts_at)->format('Y-m-d\TH:i'),
            'ends_at'   => Carbon::make($model->ends_at)->format('Y-m-d\TH:i'),
        ]);
    }

    public function clear()
    {
        $this->reset();
        $this->resetErrorBag();
    }

    public function shouldRequireSemester()
    {
        if (isset($this->evaluatee_role_id) && isset($this->evaluator_role_id)) {
            $evaluator = Role::whereId($this->evaluator_role_id)->first(['code'])?->code;
            $evaluatee = Role::whereId($this->evaluatee_role_id)->first(['code'])?->code;

            if (
                ($evaluator === RoleCode::Student->value && $evaluatee === RoleCode::Teacher->value)
                || $evaluator === RoleCode::Teacher->value && $evaluatee === RoleCode::Teacher->value
                || $evaluator === RoleCode::Dean->value && $evaluatee === RoleCode::Teacher->value
            ) {
                return true;
            }
            return false;
        }

        return false;
    }
}
