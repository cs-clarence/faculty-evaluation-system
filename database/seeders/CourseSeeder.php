<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Department;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    private $defaults = [
        [
            'department_code' => 'CASSED',
            'code' => 'BEED',
            'name' => 'Bachelor of Elementary Education',
        ],
        [
            'department_code' => 'CASSED',
            'code' => 'BSED',
            'name' => 'Bachelor of Secondary Education',
        ],
        [
            'department_code' => 'CASSED',
            'code' => 'BACOMM',
            'name' => 'Bachelor of Arts in Communication',
        ],
        [
            'department_code' => 'CASSED',
            'code' => 'BSSW',
            'name' => 'Bachelor of Science in Social Work',
        ],
        [
            'department_code' => 'COB',
            'code' => 'BSA',
            'name' => 'Bachelor of Science in Accountancy',
        ],
        [
            'department_code' => 'COB',
            'code' => 'BSBA',
            'name' => 'Bachelor of Science in Business Administration',
        ],
        [
            'department_code' => 'COB',
            'code' => 'BSCA',
            'name' => 'Bachelor of Sciene in Customs Administration',
        ],
        [
            'department_code' => 'COB',
            'code' => 'BSREM',
            'name' => 'Bachelor of Sciene in Real Estate Management',
        ],
        [
            'department_code' => 'CCIS',
            'code' => 'BSCS',
            'name' => 'Bachelor of Science in Computer Science',
        ],
        [
            'department_code' => 'CCIS',
            'code' => 'BSIT',
            'name' => 'Bachelor of Science in Information Technology',
        ],
        [
            'department_code' => 'CCIS',
            'code' => 'BSIT',
            'name' => 'Bachelor of Science in Information Systems',
        ],
        [
            'department_code' => 'CCIS',
            'code' => 'BSEMC',
            'name' => 'Bachelor of Science in Entertainment and Media Computing',
        ],
        [
            'department_code' => 'COC',
            'code' => 'BSCRIM',
            'name' => 'Bachelor of Science in Criminology',
        ],
        [
            'department_code' => 'COE',
            'code' => 'BSCOMPENG',
            'name' => 'Bachelor of Science in Computer Engineering',
        ],
        [
            'department_code' => 'COE',
            'code' => 'BSECE',
            'name' => 'Bachelor of Science in Electrical and Communications Engineering',
        ],
        [
            'department_code' => 'CHTM',
            'code' => 'BSTOUR',
            'name' => 'Bachelor of Science in Tourism',
        ],
        [
            'department_code' => 'CHTM',
            'code' => 'BSHM',
            'name' => 'Bachelor of Science in Hospitality Management',
        ],
        [
            'department_code' => 'CON',
            'code' => 'BSNURSING',
            'name' => 'Bachelor of Science in Nursing',
        ],
    ];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->defaults as $course) {
            if (Course::where('code', $course['code'])->exists()) {
                continue;
            }

            Course::create([
                'code' => $course['code'],
                'name' => $course['name'],
                'department_id' => Department::where('code', $course['department_code'])
                    ->firstOrFail(['id'])->id,
            ]);
        }

    }
}
