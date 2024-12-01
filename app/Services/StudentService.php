<?php

namespace App\Services;

use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\User;
use Log;

class StudentService
{
    private static function getUser(User | int $user)
    {
        return $user instanceof User ? $user : User::find($user)->first();
    }

    private static function getUserId(User | int $user)
    {
        return $user instanceof User ? $user->id : $user;
    }

    private static function getSchoolYear(SchoolYear | int $schoolYear)
    {
        return $schoolYear instanceof SchoolYear
        ? $schoolYear
        : SchoolYear::find($schoolYear)->first();
    }

    private static function getSchoolYearId(SchoolYear | int $schoolYear)
    {
        return $schoolYear instanceof SchoolYear
        ? $schoolYear->id
        : $schoolYear;
    }

    public function create(User | int $user, string $studentNumber, int $courseId, SchoolYear | int $startingSchoolYear)
    {
        $user = self::getUser($user);
        $schoolYear = self::getSchoolYear($startingSchoolYear);
        $student = new Student([
            'user_id' => $user->id,
            'student_number' => $studentNumber,
            'course_id' => $courseId,
            'starting_school_year_id' => $schoolYear->id,
        ]);

        $student->save();

        return $student;
    }

    public function update(User | int $user, string $studentNumber, int $courseId, SchoolYear | int $startingSchoolYear, bool $recreateSubjects = false)
    {
        Log::info("Starting School Year: {$startingSchoolYear}");
        $userId = self::getUserId($user);
        $schoolYearId = self::getSchoolYearId($startingSchoolYear);
        $student = Student::whereUserId($userId)->first();
        Log::info("School Year Id {$schoolYearId}");

        $isSameCourse = $student->course_id === $courseId;
        $isSameSchoolYear = $student->starting_school_year_id === $schoolYearId;

        if ((!$isSameCourse || !$isSameSchoolYear) && $recreateSubjects) {
            //
        }

        $student->fill([
            'student_number' => $studentNumber,
            'course_id' => $courseId,
            'starting_school_year_id' => $schoolYearId,
        ]);

        $student->save();

        return $student;
    }
}
