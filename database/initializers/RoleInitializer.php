<?php

namespace Database\Initializers;

use App\Models\Role;
use Database\Initializers\Base\Initializer;

class RoleInitializer extends Initializer
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Role::where('code', 'admin')->exists()) {
            Role::factory()->admin()->create();
        }

        if (!Role::where('code', 'student')->exists()) {
            Role::factory()->student()->create();
        }

        if (!Role::where('code', 'teacher')->exists()) {
            Role::factory()->teacher()->create();
        }
    }
}
