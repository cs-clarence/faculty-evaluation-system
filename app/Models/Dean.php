<?php
namespace App\Models;

use App\Models\Traits\Archivable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperDean
 */
class Dean extends Model
{
    /** @use HasFactory<\Database\Factories\DeanFactory> */
    use HasFactory;
    use Archivable {
        archive as baseArchive;
        unarchive as baseUnarchive;
    }

    protected $table = 'deans';

    protected $fillable = [
        'user_id',
        'department_id',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
