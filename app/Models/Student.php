<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperStudent
 */
class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_number',
        'name',
        'address',
    ];
    protected $table = 'students';

    public function user()
    {

        return $this->belongsTo(User::class);

    }
}
