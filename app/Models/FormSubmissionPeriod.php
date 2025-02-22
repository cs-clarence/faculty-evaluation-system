<?php
namespace App\Models;

use App\Models\Traits\Archivable;
use App\Models\Traits\FullTextSearchable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperFormSubmissionPeriod
 */
class FormSubmissionPeriod extends Model
{
    use Archivable, FullTextSearchable;
    //
    protected $table    = 'form_submission_periods';
    protected $fillable = ['form_id', 'name', 'starts_at', 'ends_at', 'is_open', 'is_submissions_editable', 'evaluator_role_id', 'evaluatee_role_id'];

    public function casts()
    {
        return [
            'starts_at' => 'datetime:Y-m-d\Tg:i a',
            'ends_at'   => 'datetime:Y-m-d\Tg:i a',
        ];
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function open()
    {
        $this->is_open = true;
        $this->save();
    }

    public function close()
    {
        $this->is_open = false;
        $this->save();
    }

    public function hasDependents(): bool
    {
        $submissionCount = isset($this->submissions_count) ? $this->submissions_count : $this->submissions()->count();

        if ($submissionCount > 0) {
            return true;
        }

        return false;
    }

    public function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d\Tg:i a');
    }

    public function scopeIsOpen(Builder $builder)
    {
        $now = now();
        return $builder
            ->where('is_open', true)
            ->where('starts_at', '<=', $now)
            ->where('ends_at', '>=', $now);
    }

    public function submissions()
    {
        return $this->hasMany(FormSubmission::class, 'form_submission_period_id');
    }

    public function evaluatorRole()
    {
        return $this->belongsTo(Role::class, 'evaluator_role_id');
    }

    public function evaluateeRole()
    {
        return $this->belongsTo(Role::class, 'evaluatee_role_id');
    }

    public function formSubmissionPeriodSemester()
    {
        return $this->hasOne(FormSubmissionPeriodSemester::class);
    }

    public function getSemester()
    {
        return $this->formSubmissionPeriodSemester()->first()?->semester();
    }

    protected function semester(): Attribute
    {
        return Attribute::make(fn() => $this->getSemester()->first())->shouldCache();
    }

    public function scopeEvaluator(Builder $builder, RoleCode | string $roleCode)
    {
        $roleId = Role::whereCode(is_string($roleCode) ? $roleCode : $roleCode->value)->first(['id'])->id;
        return $builder->whereEvaluatorRoleId($roleId);
    }

    public function scopeEvaluatee(Builder $builder, RoleCode | string $roleCode)
    {
        $roleId = Role::whereCode(is_string($roleCode) ? $roleCode : $roleCode->value)->first(['id'])->id;
        return $builder->whereEvaluateeRoleId($roleId);
    }
}
