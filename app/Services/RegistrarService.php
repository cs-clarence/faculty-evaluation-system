<?php
namespace App\Services;

use App\Models\Registrar;
use App\Models\User;

class RegistrarService
{
    private static function getUserId(User | int $user)
    {
        return $user instanceof User ? $user->id : $user;
    }

    public function create(User | int $user)
    {
        $userId = self::getUserId($user);

        $evaluator = new Registrar([
            'user_id' => $userId,
        ]);

        $evaluator->save();

        return $evaluator;
    }
}
