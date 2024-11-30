<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\RoleCode;
use App\Models\Traits\Archivable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use Archivable {
        Archivable::archive as baseArchive;
        Archivable::unarchive as baseUnarchive;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'role_id',
        'password',
    ];
    protected $table = 'users';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //Relationship for the role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function archive()
    {
        $teacher = $this->teacher;

        if (isset($teacher)) {
            $teacher->baseArchive();
            return;
        }

        $student = $this->student;

        if (isset($student)) {
            $student->baseArchive();
            return;
        }

        $this->baseArchive();
    }

    public function unarchive()
    {
        $teacher = $this->teacher;

        if (isset($teacher)) {
            $teacher->baseUnarchive();
            return;
        }

        $student = $this->student;

        if (isset($student)) {
            $student->baseUnarchive();
            return;
        }

        $this->baseUnarchive();
    }

    public function scopeRole(Builder $builder, RoleCode $code)
    {
        $builder->whereHas('role', function (Builder $builder) use ($code) {
            $builder->where('code', $code->value);
        });
    }

    public function scopeAdmin(Builder $builder)
    {
        $this->scopeRole($builder, RoleCode::Admin);
    }

    public function scopeStudent(Builder $builder)
    {
        $this->scopeRole($builder, RoleCode::Student);
    }
    public function scopeTeacher(Builder $builder)
    {
        $this->scopeRole($builder, RoleCode::Teacher);
    }

    public function delete()
    {
        $teacherId = $this->teacher()->first(['id'])?->id;
        $studentId = $this->student()->first(['id'])?->id;
        $userId = $this->id;

        if (isset($teacherId)) {
            Teacher::whereId($teacherId)->delete();
        }

        if (isset($studentId)) {
            Student::whereId($studentId)->delete();
        }

        User::whereId($userId)->delete();
    }

    public function hasDependents()
    {
        return false;
    }

    public function isCurrentUser(?User $user = null)
    {
        if (isset($user)) {
            return $this->id === $user->id;
        }

        return Auth::user()->id === $this->id;
    }
}
