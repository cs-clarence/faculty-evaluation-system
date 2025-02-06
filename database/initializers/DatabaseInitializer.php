<?php
namespace Database\Initializers;

use Database\Initializers\Base\Initializer;
use DB;

class DatabaseInitializer extends Initializer
{

    public function run(): void
    {
        DB::transaction(function () {
            $this->call([
                RoleInitializer::class,
                SchoolYearInitializer::class,
                SubjectInitializer::class,
                DepartmentInitializer::class,
                CourseInitializer::class,
                SectionInitializer::class,
                FormInitializer::class,
                AdminInitializer::class,
            ]);
        });

    }
}
