<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperDepartment
 */
class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';

    protected $fillable = [
        'code',
        'name',
    ];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
