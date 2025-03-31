<?php
namespace App\Models;

use App\Models\Traits\FullTextSearchable;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperLlmResponse
 */
class LlmResponse extends Model
{
    use FullTextSearchable;
    protected $fillable = ['key', 'value'];
}
