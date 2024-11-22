<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperRole
 */
class Role extends Model
{
    /** @use HasFactory<\Database\Factories\RoleFactory> */
    use HasFactory;

    //
    protected $fillable = ['name'];
    protected $table = 'roles';

    //relationship in the user
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
