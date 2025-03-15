<?php
namespace App\Models;

use App\Models\Traits\Archivable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperRegistrar
 */
class Registrar extends Model
{
    /** @use HasFactory<\Database\Factories\RegistrarFactory> */
    use HasFactory;
    use Archivable {
        archive as baseArchive;
        unarchive as baseUnarchive;
    }

    protected $table = 'registrars';

    protected $fillable = [
        'user_id',
    ];
}
