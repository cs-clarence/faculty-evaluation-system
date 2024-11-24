<?php

namespace Database\Seeders;

use App\Facades\Helpers\SectionHelper;
use App\Models\Course;
use App\Models\Section;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $yearLevels = [1, 2, 3, 4];
        $semesters = [1, 2, 3];
        $sectionNames = ['A', 'B', 'C', 'D'];
        $courses = Course::all();

        foreach ($courses as $course) {
            foreach ($yearLevels as $yearLevel) {
                foreach ($semesters as $semester) {
                    foreach ($sectionNames as $sectionName) {
                        $code = SectionHelper::generateCode($course->id, $yearLevel, $semester, $sectionName);
                        if (!Section::whereCode($code)->exists()) {
                            Section::create([
                                'course_id' => $course->id,
                                'year_level' => $yearLevel,
                                'semester' => $semester,
                                'name' => $sectionName,
                                'code' => $code,
                            ]);
                        }
                    }
                }
            }
        }

    }
}
