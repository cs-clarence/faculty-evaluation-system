<?php
namespace App\Policies\Base;

use App\Models\RoleCode;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * @template T
 */
abstract class BasePolicy
{
    /**
     * @var RoleCode[]
     */
    protected array $roleCodes;
    protected string $model;

    public function __construct(string $model, RoleCode ...$roleCodes)
    {
        $this->roleCodes = $roleCodes;
        $this->model     = $model;
    }

    protected function isInRoles(User $user, RoleCode ...$roleCodes): Response
    {
        if (empty($roleCodes)) {
            $roleCodes = $this->roleCodes;
        }

        foreach ($roleCodes as $roleCode) {
            if ($user->role->code === $roleCode->value) {
                return Response::allow();
            }
        }

        $model     = preg_split("/\\\/", $this->model);
        $lastIndex = count($model) - 1;
        $model     = $model[$lastIndex];

        return Response::deny("You are not authorized to view any {$model}.");
    }
}
