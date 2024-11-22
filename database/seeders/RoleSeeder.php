<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Role::where('code', 'admin')->exists()) {
            Role::factory(Role::factory()->admin())->create();
        }

        if (!Role::where('code', 'student')->exists()) {
            Role::factory(Role::factory()->student())->create();
        }

        if (!Role::where('code', 'teacher')->exists()) {
            Role::factory(Role::factory()->teacher())->create();
        }
    }
}
