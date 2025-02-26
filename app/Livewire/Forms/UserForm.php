<?php
namespace App\Livewire\Forms;

use App\Facades\Services\UserService;
use App\Models\Role;
use App\Models\RoleCode;
use App\Models\User;
use DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Locked;
use stdClass;

/**
 * @extends parent<User>
 */
class UserForm extends BaseForm
{
    #[Locked]
    public ?int $id;
    public ?string $email = null;
    public ?string $name = null;
    public ?string $password = null;
    public ?string $password_confirmation = null;
    public ?string $role_code = null;
    public ?string $student_number = null;
    public ?int $course_id = null;
    public ?int $department_id = null;
    public ?int $starting_school_year_id = null;
    public bool $include_password = true;
    public bool $include_base = true;
    public bool $realign_subjects = true;
    public bool $delete_subjects_from_previous_course = false;
    public ?bool $active = true;
    public ?bool $require_change_password = false;

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
                'course_id'               => [$isStudent ? 'required' : 'nullable', 'integer', 'exists:courses,id'],
                'student_number'          => [$isStudent ? 'required' : 'nullable', 'string', ! isset($this->id) ? 'unique:students' :
                    Rule::unique('students', 'student_number')
                        ->ignore($this->id, 'user_id'),

                ],
                'starting_school_year_id' => [$isStudent ? 'required' : 'nullable', 'integer', 'exists:school_years,id'],
                'department_id'           => [$isTeacher ? 'required' : 'nullable', 'integer', 'exists:departments,id'],
                'active'                  => ['nullable', 'boolean'],
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

    /**
     * @param \App\Models\User $model
     * @return void
     */
    public function set(mixed $model)
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
                    $extras = new stdClass;

                    if ($this->role_code === RoleCode::Student->value) {
                        $extras->studentNumber                    = $this->student_number;
                        $extras->courseId                         = $this->course_id;
                        $extras->startingSchoolYearId             = $this->starting_school_year_id;
                        $extras->realignSubjects                  = $this->realign_subjects;
                        $extras->deleteSubjectsFromPreviousCourse = $this->delete_subjects_from_previous_course;
                    }

                    if ($this->role_code === RoleCode::Teacher->value || $this->role_code === RoleCode::Dean->value) {
                        $extras->departmentId = $this->department_id;
                    }

                    UserService::update(
                        $user,
                        $this->name,
                        $this->email,
                        RoleCode::from($this->role_code),
                        $this->active ?? false,
                        $this->require_change_password,
                        $extras
                    );
                }
            } else {
                $extras = new stdClass;

                if ($this->role_code === RoleCode::Student->value) {
                    $extras->studentNumber        = $this->student_number;
                    $extras->courseId             = $this->course_id;
                    $extras->startingSchoolYearId = $this->starting_school_year_id;
                }

                if ($this->role_code === RoleCode::Teacher->value || $this->role_code === RoleCode::Dean->value) {
                    $extras->departmentId = $this->department_id;
                }

                $user = UserService::create($this->email, $this->name, $this->password, RoleCode::from($this->role_code), $this->active ?? false, $this->require_change_password, $extras);

            }
        });
    }
}
