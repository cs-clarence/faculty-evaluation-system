<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    private static $defaults = [
        [
            'code' => 'CUS',
            'name' => 'Understanding the Self',
        ],
        [
            'code' => 'CCW',
            'name' => 'The Contemporary World',
        ],
        [
            'code' => 'ANH',
            'name' => 'Philippine Popular Culture',
        ],
        [
            'code' => 'DMATH',
            'name' => 'Discrete Mathematics',
        ],
        [
            'code' => 'ITC',
            'name' => 'Introduction to Computing',
        ],
        [
            'code' => 'PROG1L',
            'name' => 'Fundamentals of Programming',
        ],
        [
            'code' => 'PE_1',
            'name' => 'Movement Enhancement',
        ],
        [
            'code' => 'NSTP_1',
            'name' => 'Civil Welfare Training Service 1',
        ],
    ];

    public function run(): void
    {
        foreach (self::$defaults as $subject) {
            if (!Subject::whereCode($subject['code'])->exists()) {
                Subject::create($subject);
            }
        }
    }
}
