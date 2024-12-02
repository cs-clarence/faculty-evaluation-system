<?php

namespace App\Models;

use App\Models\Traits\Archivable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperFormSubmissionPeriod
 */
class FormSubmissionPeriod extends Model
{
    use Archivable;
    //
    protected $table = 'form_submission_periods';
    public $fillable = ['form_id', 'name', 'starts_at', 'ends_at', 'semester_id', 'is_open', 'is_submissions_editable'];

    public function casts()
    {
        return [
            'starts_at' => 'datetime:Y-m-d\Tg:i a',
            'ends_at' => 'datetime:Y-m-d\Tg:i a',
        ];
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
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
}
