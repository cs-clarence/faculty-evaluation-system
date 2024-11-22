<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperSubject
 */
class Subject extends Model
{
    use HasFactory;

    protected $table = 'subjects';

    protected $fillable = [
        'code',
        'name',
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_subject', 'subject_id', 'course_id');
    }
}
