<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperForm
 */
class Form extends Model
{
    /** @use HasFactory<\Database\Factories\FormFactory> */
    use HasFactory;

    protected $table = 'forms';
}
