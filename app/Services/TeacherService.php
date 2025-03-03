<?php
namespace App\Services;

use App\Models\Teacher;
use App\Models\User;

class TeacherService
{
    private static function getUserId(User | int $user)
    {
        return $user instanceof User ? $user->id : $user;
    }

    public function create(User | int $user, int $department_id)
    {
        $userId = self::getUserId($user);

        $teacher = new Teacher([
            'user_id'       => $userId,
            'department_id' => $department_id,
        ]);

        $teacher->save();

        return $teacher;
    }

    public function update(User | int $user, int $department_id)
    {
        $userId = self::getUserId($user);

        $teacher = Teacher::whereUserId($userId)->first();

        $teacher->update(['department_id' => $department_id]);

        return $teacher;
    }
}
