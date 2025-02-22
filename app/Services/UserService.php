<?php
namespace App\Services;

use App\Models\Role;
use App\Models\RoleCode;
use App\Models\User;
use Hash;

class UserService
{
    public function create(
        string $email,
        string $name,
        string $password,
        RoleCode $roleCode,
        bool $active,
    ): User {
        $user = new User([
            'email'    => $email,
            'name'     => $name,
            'password' => Hash::make($password),
            'role_id'  => Role::whereCode($roleCode->value)->first(['id'])->id,
            'active'   => $active,
        ]);

        $user->save();

        return $user;
    }

    public function updatePassword(User | int $user, string $password)
    {
        $user           = $user instanceof User ? $user : User::find($user)->first();
        $user->password = Hash::make($password);
        $user->save();

        return $user;
    }

    public function update(User | int $user, string $name, string $email, RoleCode $roleCode, bool $active)
    {
        $user   = $user instanceof User ? $user : User::find($user)->first();
        $roleId = Role::whereCode($roleCode)->first(['id'])->id;

        $user->update([
            'name'    => $name,
            'email'   => $email,
            'role_id' => $roleId,
            'active'  => $active,
        ]);
        return $user;
    }
}
