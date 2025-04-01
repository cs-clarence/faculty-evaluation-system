<?php
namespace Database\Initializers;

use App\Facades\Helpers\SectionHelper;
use App\Models\Course;
use App\Models\Section;
use Database\Initializers\Base\Initializer;

class SectionInitializer extends Initializer
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (env('APP_ENV') === 'production') {
            return;
        }
        $yearLevels   = [1, 2, 3, 4];
        $semesters    = [1, 2, 3];
        $sectionNames = ['A', 'B', 'C', 'D'];
        $courses      = Course::all();

        foreach ($courses as $course) {
            foreach ($yearLevels as $yearLevel) {
                foreach ($semesters as $semester) {
                    foreach ($sectionNames as $sectionName) {
                        $code = SectionHelper::generateCode($course->id, $yearLevel, $semester, $sectionName);
                        if (! Section::whereCode($code)->exists()) {
                            Section::create([
                                'course_id'  => $course->id,
                                'year_level' => $yearLevel,
                                'semester'   => $semester,
                                'name'       => $sectionName,
                                'code'       => $code,
                            ]);
                        }
                    }
                }
            }
        }

    }
}
