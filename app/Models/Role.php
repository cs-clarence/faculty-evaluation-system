<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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
    protected $fillable = ['display_name', 'role'];
    protected $table    = 'roles';

    //relationship in the user
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function scopeCanBeEvaluator(Builder $builder)
    {
        return $builder->where('can_be_evaluator', true);
    }

    public function scopeCanBeEvaluatee(Builder $builder)
    {
        return $builder->where('can_be_evaluatee', true);
    }

    public function __tostring()
    {
        return $this->display_name;
    }
}
