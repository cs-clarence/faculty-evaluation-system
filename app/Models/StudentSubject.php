<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @mixin IdeHelperStudentSubject
 */
class StudentSubject extends Pivot
{
    public $incrementing = true;
    //
    protected $table = 'student_subjects';
}
