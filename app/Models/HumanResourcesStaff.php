<?php
namespace App\Models;

use App\Models\Traits\Archivable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperHumanResourcesStaff
 */
class HumanResourcesStaff extends Model
{
    /** @use HasFactory<\Database\Factories\HumanResourcesStaffFactory> */
    use HasFactory;
    use Archivable {
        archive as baseArchive;
        unarchive as baseUnarchive;
    }

    protected $table = 'human_resources_staff';

    protected $fillable = [
        'user_id',
    ];
}
