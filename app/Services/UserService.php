<?php
namespace App\Services;

use App\Models\Role;
use App\Models\RoleCode;
use App\Models\User;
use Hash;

class UserService
{
    public function __construct(
        private StudentService $studentService,
        private TeacherService $teacherService,
        private DeanService $deanService,
        private HumanResourcesStaffService $humanResourcesStaffService,
        private EvaluatorService $evaluatorService
    ) {

    }

    public function create(
        string $email,
        string $name,
        string $password,
        RoleCode $roleCode,
        bool $active,
        bool $requireChangePassword = false,
        mixed $extras = null,
    ): User {
        $user = new User([
            'email'                   => $email,
            'name'                    => $name,
            'password'                => Hash::make($password),
            'role_id'                 => Role::whereCode($roleCode->value)->first(['id'])->id,
            'require_change_password' => $requireChangePassword,
            'active'                  => $active,
        ]);

        $user->save();

        if ($roleCode === RoleCode::Student) {
            $this->studentService->create($user,
                $extras->studentNumber,
                $extras->courseId,
                $extras->startingSchoolYearId,
            );
        }
        if ($roleCode === RoleCode::Teacher) {
            $this->teacherService->create($user, $extras->departmentId);
        }

        if ($roleCode === RoleCode::Dean) {
            $this->deanService->create($user, $extras->departmentId);
        }

        if ($roleCode === RoleCode::Evaluator) {
            $this->evaluatorService->create($user);
        }

        if ($roleCode === RoleCode::HumanResourcesStaff) {
            $this->humanResourcesStaffService->create($user);
        }

        return $user;
    }

    public function updatePassword(User | int $user, string $password)
    {
        $user           = $user instanceof User ? $user : User::find($user)->first();
        $user->password = Hash::make($password);
        $user->save();

        return $user;
    }

    public function update(User | int $user, string $name, string $email, RoleCode $roleCode, bool $active, bool $requireChangePassword, mixed $extras = null)
    {
        $user = $user instanceof User ? $user : User::find($user)->first();
        $role = Role::whereCode($roleCode)->first(['id']);

        $user->update([
            'name'                    => $name,
            'email'                   => $email,
            'role_id'                 => $role->id,
            'active'                  => $active,
            'require_change_password' => $requireChangePassword,
        ]);

        if ($roleCode === RoleCode::Student) {
            if (isset($user->student)) {
                $this->studentService->update($user,
                    $extras->studentNumber,
                    $extras->courseId,
                    $extras->startingSchoolYearId,
                    $extras->realignSubjects,
                    $extras->deleteSubjectsFromPreviousCourse,
                );
            } else {
                $this->studentService->create($user,
                    $extras->studentNumber,
                    $extras->courseId,
                    $extras->startingSchoolYearId,
                );
            }
        }
        if ($roleCode === RoleCode::Teacher) {
            if (isset($user->teacher)) {
                $this->teacherService->update($user, $extras->departmentId);
            } else {
                $this->teacherService->create($user, $extras->departmentId);
            }
        }

        if ($roleCode === RoleCode::Dean) {
            if (isset($user->dean)) {
                $this->deanService->update($user, $extras->departmentId);
            } else {
                $this->deanService->create($user, $extras->departmentId);
            }
        }

        if ($roleCode === RoleCode::Evaluator) {
            if (! isset($user->evaluator)) {
                $this->evaluatorService->create($user);
            }
        }

        if ($roleCode === RoleCode::HumanResourcesStaff) {
            if (! isset($user->humanResourcesStaff)) {
                $this->humanResourcesStaffService->create($user);
            }
        }

        return $user;
    }
}
