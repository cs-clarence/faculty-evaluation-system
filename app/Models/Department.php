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

    public function archive()
    {
        $this->archived_at = now();
        $this->save();
    }

    public function unarchive()
    {
        $this->archived_at = null;
        $this->save();
    }

}
