<?php

namespace App\Models;

use App\Models\Traits\Archivable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperForm
 */
class Form extends Model
{
    /** @use HasFactory<\Database\Factories\FormFactory> */
    use HasFactory, Archivable;

    protected $table = 'forms';
    public $fillable = ['name', 'description'];

    public function sections(): HasMany
    {
        return $this->hasMany(FormSection::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(FormQuestion::class);
    }

    public function submissionPeriods(): HasMany
    {
        return $this->hasMany(FormSubmissionPeriod::class);
    }

    public function hasDependents(): bool
    {
        $spCount = isset($this->submission_periods_count) ? $this->submission_periods_count : $this->submissionPeriods()->count();

        if ($spCount > 0) {
            return true;
        }

        return false;
    }
}
