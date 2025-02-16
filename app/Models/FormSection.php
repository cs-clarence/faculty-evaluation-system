<?php
namespace App\Models;

use App\Models\Traits\Reorderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperFormSection
 */
class FormSection extends Model
{
    use Reorderable;

    protected $table = 'form_sections';
    public $fillable = ['title', 'description', 'form_id', 'order_numerator', 'order_denominator'];

    public function questions(): HasMany
    {
        return $this->hasMany(FormQuestion::class);
    }

}
