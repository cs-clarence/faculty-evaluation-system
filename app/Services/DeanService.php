<?php
namespace App\Services;

use App\Models\Dean;
use App\Models\User;

class DeanService
{
    private static function getUserId(User | int $user)
    {
        return $user instanceof User ? $user->id : $user;
    }

    public function create(User | int $user, int $department_id)
    {
        $userId = self::getUserId($user);

        $dean = new Dean([
            'user_id'       => $userId,
            'department_id' => $department_id,
        ]);

        $dean->save();

        return $dean;
    }

    public function update(User | int $user, int $department_id)
    {
        $userId = self::getUserId($user);

        $dean = Dean::whereUserId($userId)->first();

        $dean->update(['department_id' => $department_id]);

        return $dean;
    }
}
