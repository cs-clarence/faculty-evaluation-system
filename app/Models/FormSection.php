<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperFormSection
 */
class FormSection extends Model
{
    protected $table = 'form_sections';
    public $fillable = ['name', 'description', 'form_id'];

    public function questions(): HasMany
    {
        return $this->hasMany(FormQuestion::class);
    }
}
