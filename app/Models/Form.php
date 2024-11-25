<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperForm
 */
class Form extends Model
{
    /** @use HasFactory<\Database\Factories\FormFactory> */
    use HasFactory;

    protected $table = 'forms';
    public $fillable = ['name', 'description'];

    public function sections(): HasMany
    {
        return $this->hasMany(FormSection::class);
    }
}
