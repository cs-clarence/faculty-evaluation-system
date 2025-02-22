<?php
namespace Database\Initializers;

use App\Models\Role;
use App\Models\User;
use Database\Initializers\Base\Initializer;
use Illuminate\Support\Facades\Hash;

class AdminInitializer extends Initializer
{

    public function run(): void
    {
        $roleId = Role::where('code', 'admin')->first(['id'])->id;
        if (! User::where('role_id', $roleId)->exists()) {
            User::factory()->admin()->create([
                'email'  => 'admin@gmail.com', 'password' => Hash::make('adminuser'), 'name' => 'Admin User',
                'active' => true,
            ]);
        }
    }
}
