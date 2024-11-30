<?php

namespace Database\Initializers;

use Database\Initializers\Base\Initializer;

class DatabaseInitializer extends Initializer
{

    public function run(): void
    {
        $this->call([
            RoleInitializer::class,
            SchoolYearInitializer::class,
            SubjectInitializer::class,
            DepartmentInitializer::class,
            CourseInitializer::class,
            SectionInitializer::class,
            FormInitializer::class,
        ]);
    }
}
