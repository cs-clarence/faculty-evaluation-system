<?php
namespace App\Models;

use App\Models\Traits\Archivable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperEvaluator
 */
class Evaluator extends Model
{
    /** @use HasFactory<\Database\Factories\EvaluatorFactory> */
    use HasFactory;
    use Archivable {
        archive as baseArchive;
        unarchive as baseUnarchive;
    }

    protected $table = 'evaluators';

    protected $fillable = [
        'user_id',
    ];
}
