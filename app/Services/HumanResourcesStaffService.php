<?php
namespace App\Services;

use App\Models\HumanResourcesStaff;
use App\Models\User;

class HumanResourcesStaffService
{
    private static function getUserId(User | int $user)
    {
        return $user instanceof User ? $user->id : $user;
    }

    public function create(User | int $user)
    {
        $userId = self::getUserId($user);

        $hr = new HumanResourcesStaff([
            'user_id' => $userId,
        ]);

        $hr->save();

        return $hr;
    }
}
