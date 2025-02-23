<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\RoleCode;
use App\Models\Traits\Archivable;
use App\Models\Traits\FullTextSearchable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Attributes\SearchUsingPrefix;
use Laravel\Scout\Searchable;

/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable
{
    /**
     * @use HasFactory<\Database\Factories\UserFactory>
     */
    use Searchable;
    use FullTextSearchable;
    use HasFactory;
    use HasApiTokens, Notifiable;
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
        'active',
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

    #[SearchUsingFullText(['name', 'email'])]
    #[SearchUsingPrefix(['id'])]

    public function toSearchableArray()
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'email' => $this->email,
        ];
    }

    //Relationship for the role
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function dean()
    {
        return $this->hasOne(Dean::class);
    }

    public function evaluator()
    {
        return $this->hasOne(Evaluator::class);
    }

    public function humanResourcesStaff()
    {
        return $this->hasOne(HumanResourcesStaff::class);

    }

    public function archive()
    {
        $teacher = $this->teacher;

        if (isset($teacher)) {
            $teacher->baseArchive();
        }

        $student = $this->student;

        if (isset($student)) {
            $student->baseArchive();
        }

        $dean = $this->dean;

        if (isset($dean)) {
            $dean->baseArchive();
        }

        $evaluator = $this->evaluator;

        if (isset($evaluator)) {
            $evaluator->baseArchive();
        }

        $humanResourcesStaff = $this->humanResourcesStaff;

        if (isset($humanResourcesStaff)) {
            $evaluator->baseArchive();
        }

        $this->baseArchive();
    }

    public function unarchive()
    {
        $teacher = $this->teacher;

        if (isset($teacher)) {
            $teacher->baseUnarchive();
        }

        $student = $this->student;

        if (isset($student)) {
            $student->baseUnarchive();
        }

        $dean = $this->dean;

        if (isset($dean)) {
            $dean->baseUnarchive();
        }

        $evaluator = $this->evaluator;

        if (isset($evaluator)) {
            $evaluator->baseUnarchive();
        }

        $humanResourcesStaff = $this->humanResourcesStaff;

        if (isset($humanResourcesStaff)) {
            $evaluator->baseUnarchive();
        }

        $this->baseUnarchive();
    }

    public function scopeRole(Builder $builder, RoleCode $code)
    {
        return $builder->whereHas('role', function (Builder $builder) use ($code) {
            $builder->where('code', $code->value);
        });
    }

    public function scopeRoleAdmin(Builder $builder)
    {
        return $this->scopeRole($builder, RoleCode::Admin);
    }

    public function scopeRoleStudent(Builder $builder)
    {
        return $this->scopeRole($builder, RoleCode::Student);
    }
    public function scopeRoleTeacher(Builder $builder)
    {
        return $this->scopeRole($builder, RoleCode::Teacher);
    }

    public function scopeRoleDean(Builder $builder)
    {
        return $this->scopeRole($builder, RoleCode::Dean);
    }

    public function delete()
    {
        $teacherId             = $this->teacher()->first(['id'])?->id;
        $studentId             = $this->student()->first(['id'])?->id;
        $deanId                = $this->dean()->first(['id'])?->id;
        $evaluatorId           = $this->evaluator()->first(['id'])?->id;
        $humanResourcesStaffId = $this->humanResourcesStaff()->first(['id'])?->id;
        $userId                = $this->id;

        if (isset($teacherId)) {
            Teacher::whereId($teacherId)->delete();
        }

        if (isset($studentId)) {
            Student::whereId($studentId)->delete();
        }

        if (isset($deanId)) {
            Dean::whereId($deanId)->delete();
        }

        if (isset($evaluatorId)) {
            Evaluator::whereId($evaluatorId)->delete();
        }

        if (isset($humanResourcesStaffId)) {
            HumanResourcesStaff::whereId($humanResourcesStaffId)->delete();
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

    public function isInRole(RoleCode $roleCode)
    {
        return $this->role->code === $roleCode->value;
    }

    public function isAdmin()
    {
        return $this->isInRole(RoleCode::Admin);
    }

    public function isStudent()
    {
        return $this->isInRole(RoleCode::Student);
    }

    public function isTeacher()
    {
        return $this->isInRole(RoleCode::Teacher);
    }

    public function isDean()
    {
        return $this->isInRole(RoleCode::Dean);
    }

    public function isEvaluator()
    {
        return $this->isInRole(RoleCode::Evaluator);
    }

    public function isHumanResourcesStaff()
    {
        return $this->isInRole(RoleCode::HumanResourcesStaff);
    }

    public function activate()
    {
        $this->active = true;
        $this->save();
    }

    public function deactivate()
    {
        $this->active = false;
        $this->save();
    }

    public function scopeActive(Builder $builder)
    {
        return $builder->whereActive(true);
    }

    public function evaluationsReceived()
    {
        return $this->hasMany(FormSubmission::class, 'evaluatee_id', 'id');
    }

    public function evaluationsSubmitted()
    {
        return $this->hasMany(FormSubmission::class, 'evaluator_id', 'id');
    }
}
