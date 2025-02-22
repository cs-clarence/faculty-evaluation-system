<?php
namespace App\Models;

use App\Models\Traits\Archivable;
use App\Models\Traits\FullTextSearchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperDepartment
 */
class Department extends Model
{
    use HasFactory, Archivable, FullTextSearchable;

    protected $table = 'departments';

    protected $fillable = [
        'code',
        'name',
    ];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function hasDependents()
    {
        $courseCount = isset($this->courses_count) ? $this->courses_count : $this->courses()->count();
        if ($courseCount > 0) {
            return true;
        }

        return false;
    }
}
