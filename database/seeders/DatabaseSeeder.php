<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $this->call([
                RoleSeeder::class,
                SchoolYearSeeder::class,
                SubjectSeeder::class,
                DepartmentSeeder::class,
                CourseSeeder::class,
                SectionSeeder::class,
                FormSeeder::class,
            ]);
        });
    }
}
