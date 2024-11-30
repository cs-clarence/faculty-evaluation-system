<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Initializers\DatabaseInitializer;
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
            (new DatabaseInitializer())->run();
        });
    }
}
