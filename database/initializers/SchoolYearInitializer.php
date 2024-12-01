<?php

namespace Database\Initializers;

use App\Facades\Services\SchoolYearService;
use App\Models\SchoolYear;
use Database\Initializers\Base\Initializer;

class SchoolYearInitializer extends Initializer
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $start = 2018;
        $until = 2026;

        for ($i = $start; $i <= $until; $i++) {
            if (!SchoolYear::whereYearStart($i)->exists()) {
                SchoolYearService::create($i, 2);
            }
        }
    }
}
