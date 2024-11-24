<?php

namespace App\Helpers;

use App\Models\Course;
use Illuminate\Support\Str;

class SectionHelper
{
    public function generateCode(int $courseId, int $yearLevel, int $semester, string $name): string
    {
        $course = Course::whereId($courseId)->first();
        $name = preg_replace('/\s+/', '_', Str::upper($name));
        $paddedYearLevel = Str::padLeft($yearLevel, 2, '0');
        $paddedSemester = Str::padLeft($semester, 2, '0');
        return "{$course->code}_Y{$paddedYearLevel}_S{$paddedSemester}_{$name}";
    }
}
