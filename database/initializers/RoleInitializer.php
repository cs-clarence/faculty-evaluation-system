<?php
namespace Database\Initializers;

use App\Models\Role;
use App\Models\RoleCode;
use Database\Initializers\Base\Initializer;

class RoleInitializer extends Initializer
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (RoleCode::cases() as $roleCode) {
            if (! Role::where('code', $roleCode->value)->exists()) {
                Role::factory()->roleCode($roleCode)->create();
            }
        }
    }
}
