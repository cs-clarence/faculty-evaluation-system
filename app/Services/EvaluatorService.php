<?php
namespace App\Services;

use App\Models\Evaluator;
use App\Models\User;

class EvaluatorService
{
    private static function getUserId(User | int $user)
    {
        return $user instanceof User ? $user->id : $user;
    }

    public function create(User | int $user)
    {
        $userId = self::getUserId($user);

        $evaluator = new Evaluator([
            'user_id' => $userId,
        ]);

        $evaluator->save();

        return $evaluator;
    }
}
