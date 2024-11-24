<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @mixin IdeHelperTeacherSubject
 */
class TeacherSubject extends Pivot
{
    //
    protected $table = 'teacher_subjects';
}
