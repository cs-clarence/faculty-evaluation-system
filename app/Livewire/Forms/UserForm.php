<?php
namespace App\Livewire\Forms;

use App\Facades\Services\DeanService;
use App\Facades\Services\EvaluatorService;
use App\Facades\Services\HumanResourcesStaffService;
use App\Facades\Services\StudentService;
use App\Facades\Services\TeacherService;
use App\Facades\Services\UserService;
use App\Models\Role;
use App\Models\RoleCode;
use App\Models\User;
use DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Locked;

class UserForm extends BaseForm
{
    #[Locked]
    public ?int $id;
    public ?string $email;
    public ?string $name;
    public ?string $password;
    public ?string $password_confirmation;
    public ?string $role_code;
    public ?string $student_number;
    public ?int $course_id = null;
    public ?int $department_id;
    public ?int $starting_school_year_id = null;
    public bool $include_password = true;
    public bool $include_base = true;
    public bool $realign_subjects = true;
    public bool $delete_subjects_from_previous_course = false;

    public function rules()
    {
        $isStudent = $this->role_code === 'student';
        $isTeacher = $this->role_code === 'teacher';

        $validators = [];

        if ($this->include_base) {
            $uniqueEmail = 'unique:users,email';
            $validators  = [
                 ...$validators,
                'email'                   => ['required', 'string', 'email', 'max:255', isset($this->id) ? $uniqueEmail . ',' . $this->id : $uniqueEmail],
                'name'                    => ['required', 'string', 'max:255'],
                'role_code'               => ['required', 'string', 'exists:roles,code'],
                'course_id'               => [$isStudent ? 'required ' : 'nullable', 'integer', 'exists:courses,id'],
                'student_number'          => [$isStudent ? 'required ' : 'nullable', 'string', ! isset($this->id) ? 'unique:students' :
                    Rule::unique('students', 'student_number')
                        ->ignore($this->id, 'user_id'),

                ],
                'starting_school_year_id' => [$isStudent ? 'required ' : 'nullable', 'integer', 'exists:school_years,id'],
                'department_id'           => [$isTeacher ? 'required ' : 'nullable', 'integer', 'exists:departments,id'],
            ];
        }

        if ($this->include_password) {
            $validators = [
                 ...$validators,
                'password' => ['required', 'confirmed', Password::defaults()],
            ];
        }

        return $validators;
    }

    public function setupEdit(
        bool $includePassword = false,
        bool $includeBase = false,
    ) {
        $this->include_password = $includePassword;
        $this->include_base     = $includeBase;
    }

    public function set(User $model)
    {
        $role    = Role::whereId($model->role_id)->first(['code']);
        $student = $role->code === RoleCode::Student->value ? $model->student()->first(['course_id', 'student_number', 'starting_school_year_id']) : null;
        $teacher = $role->code === RoleCode::Teacher->value ? $model->teacher()->first(['department_id']) : null;
        $dean    = $role->code === RoleCode::Dean->value ? $model->dean()->first(['department_id']) : null;
        $dean    = $role->code === RoleCode::Dean->value ? $model->dean()->first(['department_id']) : null;
        // $evaluator = $role->code === RoleCode::Evaluator->value ? $model->evaluator()->first(['user_id']) : null;
        // $humanResourcesStaff = $role->code === RoleCode::Evaluator->value ? $model->evaluator()->first(['user_id']) : null;

        $this->fill([ ...$model->attributesToArray(),
            'role_code'             => $role->code,
            ...($student?->attributesToArray() ?? []),
            ...($teacher?->attributesToArray() ?? []),
            ...($dean?->attributesToArray() ?? []),
            'password'              => '',
            'password_confirmation' => '',
        ]);
    }

    public function submit()
    {
        $this->validate();
        DB::transaction(function () {
            if (isset($this->id)) {
                $user = User::whereId($this->id)->first();
                if ($this->include_password) {
                    UserService::updatePassword($user, $this->password);
                }

                if ($this->include_base) {
                    UserService::update($user, $this->name, $this->email, RoleCode::from($this->role_code));
                    if ($this->role_code === RoleCode::Student->value) {
                        StudentService::update($user, $this->student_number, $this->course_id, $this->starting_school_year_id, $this->realign_subjects, $this->delete_subjects_from_previous_course);
                    }

                    if ($this->role_code === RoleCode::Teacher->value) {
                        TeacherService::update($user, $this->department_id);
                    }

                    if ($this->role_code === RoleCode::Dean->value) {
                        DeanService::update($user, $this->department_id);
                    }
                }
            } else {
                $user = UserService::create($this->email, $this->name, $this->password, RoleCode::from($this->role_code));

                if ($this->role_code === 'student') {
                    StudentService::create($user, $this->student_number, $this->course_id, $this->starting_school_year_id);
                }
                if ($this->role_code === 'teacher') {
                    TeacherService::create($user, $this->department_id);
                }

                if ($this->role_code === RoleCode::Dean->value) {
                    DeanService::create($user, $this->department_id);
                }

                if ($this->role_code === RoleCode::Evaluator->value) {
                    EvaluatorService::create($user);
                }

                if ($this->role_code === RoleCode::HumanResourcesStaff->value) {
                    HumanResourcesStaffService::create($user);
                }
            }
        });
    }
}
