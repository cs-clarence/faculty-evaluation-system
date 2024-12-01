<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        if (!User::whereEmail('tes-admin@spcf.edu.ph')->exists()) {
            User::factory()
                ->admin()
                ->email('tes-admin@spcf.edu.ph')
                ->name('tes-admin')
                ->password('tes-admin-2024')
                ->create();
        }
    }
}
