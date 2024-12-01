<?php

namespace App\Services;

use App\Models\Course;
use App\Models\SchoolYear;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentSemester;
use App\Models\StudentSubject;
use App\Models\User;

class StudentService
{
    public function __construct(
        private SchoolYearService $schoolYear,
    ) {}

    private static function getUser(User | int $user)
    {
        return $user instanceof User ? $user : User::whereId($user)->first();
    }

    private static function getUserId(User | int $user)
    {
        return $user instanceof User ? $user->id : $user;
    }

    private static function getSchoolYear(SchoolYear | int $schoolYear)
    {
        return $schoolYear instanceof SchoolYear
        ? $schoolYear
        : SchoolYear::whereId($schoolYear)->first();
    }

    private static function getSchoolYearId(SchoolYear | int $schoolYear)
    {
        return $schoolYear instanceof SchoolYear
        ? $schoolYear->id
        : $schoolYear;
    }

    private function alignStudentSubjects(
        Student $student,
        int $courseId,
        ?int $previousCourseId,
        SchoolYear $startingSchoolYear,
        ?SchoolYear $previousSchoolYear = null,
        bool $deleteSubjectsFromPreviousCourse = false
    ) {
        $course = Course::with(['courseSemesters.courseSubjects'])
            ->withCount(['courseSemesters'])
            ->whereId($courseId)
            ->firstOrFail();

        $maxSemesterCount = collect($course->courseSemesters)
            ->groupBy(fn($i) => $i->year_level)
            ->map(fn($i) => $i->count())
            ->max();

        $maxYearLevel = collect($course->courseSemesters)
            ->map(fn($i) => $i->year_level)
            ->max();

        $this->schoolYear->createMissingStartingFromYear(
            $startingSchoolYear->year_start,
            $maxYearLevel,
            $maxSemesterCount
        );

        if ($deleteSubjectsFromPreviousCourse && isset($previousCourseId)) {
            $previousCourse = Course::with(['courseSubjects'])
                ->whereId($previousCourseId)
                ->first();
            $courseSubjectIds = $previousCourse->courseSubjects->pluck('id');
            $studentSemesterIds = $student->studentSemesters->pluck('id');

            StudentSubject::whereIn('student_semester_id', $studentSemesterIds)
                ->whereIn('course_subject_id', $courseSubjectIds)
                ->delete();
        }

        if (isset($previousSchoolYear)) {
            $existingStudentSemesters = $student
                ->studentSemesters()
                ->with(['semester.schoolYear'])
                ->get();
            $startYearDiff = $startingSchoolYear->year_start - $previousSchoolYear->year_start;
            foreach ($existingStudentSemesters as $studentSemester) {
                $newStartYear = $studentSemester->semester->schoolYear->year_start + $startYearDiff;
                $newSy = SchoolYear::whereYearStart($newStartYear)->first(['id']);
                $newSem = Semester::whereBelongsTo($newSy)
                    ->whereSemester($studentSemester->semester->semester)
                    ->first(['id']);
                $studentSemester->update([
                    'semester_id' => $newSem->id,
                ]);
            }
        }

        foreach ($course->courseSemesters as $semester) {
            $year = $startingSchoolYear->year_start + $semester->year_level - 1;
            $sy = SchoolYear::whereYearStart($year)->first(['id']);
            $sem = Semester::whereSemester($semester->semester)
                ->whereSchoolYearId($sy->id)
                ->first();

            $studentSemester = StudentSemester::whereStudentId($student->id)
                ->whereSemesterId($sem->id)
                ->firstOrCreate([
                    'student_id' => $student->id,
                    'semester_id' => $sem->id,
                ]);

            $subjects = [];
            foreach ($semester->courseSubjects as $subject) {
                if (!$subject->is_archived
                    && !StudentSubject::whereStudentSemesterId($studentSemester->id)
                    ->whereCourseSubjectId($subject->id)->exists()
                ) {
                    $subjects[] = new StudentSubject([
                        'student_semester_id' => $studentSemester->id,
                        'course_subject_id' => $subject->id,
                    ]);
                }
            }
            if (count($subjects) > 0) {
                $studentSemester->studentSubjects()->saveMany($subjects);
            }
        }

        // cleanup student semesters without any subjects
        $newStudentSemesters = $student->studentSemesters()
            ->withCount(['studentSubjects'])
            ->get(['student_subjects_count', 'id']);
        foreach ($newStudentSemesters as $studentSemester) {
            if ($studentSemester->student_subjects_count === 0) {
                $studentSemester->delete();
            }
        }
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

        $this->alignStudentSubjects($student, $courseId, null, $schoolYear, null);

        return $student;
    }

    public function update(User | int $user, string $studentNumber, int $courseId, SchoolYear | int $startingSchoolYear,
        bool $realignSubjects = false, bool $deleteSubjectsFromPreviousCourse = false
    ) {
        $userId = self::getUserId($user);
        $schoolYearId = self::getSchoolYearId($startingSchoolYear);
        $schoolYear = self::getSchoolYear($startingSchoolYear);
        $student = Student::whereUserId($userId)->first();

        $isSameCourse = $student->course_id === $courseId;
        $isSameSchoolYear = $student->starting_school_year_id === $schoolYearId;

        if ((!$isSameCourse || !$isSameSchoolYear) && $realignSubjects) {
            $this->alignStudentSubjects($student, $courseId, $student->course_id,
                $schoolYear, $student->schoolYear, $deleteSubjectsFromPreviousCourse);
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
