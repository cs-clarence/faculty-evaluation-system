<?php

use App\Models\Course;
use App\Models\CourseSubject;
use App\Models\SchoolYear;
use App\Models\Semester;
use App\Models\SemesterSection;
use App\Models\Student;
use App\Models\StudentSemester;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            $table->string('student_number')->unique();
            $table->string('address')->nullable();
            $table->foreignIdFor(SchoolYear::class, 'starting_school_year_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'user_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignIdFor(Course::class, 'course_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // Add other student-specific fields here
            $table->timestampsTz();

            $table->timestampTz('archived_at')->nullable();
        });

        Schema::create('student_semesters', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Student::class, 'student_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignIdFor(Semester::class, 'semester_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestampTz('archived_at')->nullable();

            $table->unique(['student_id', 'semester_id']);

            $table->timestampsTz();
        });

        Schema::create('student_subjects', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(StudentSemester::class, 'student_semester_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignIdFor(CourseSubject::class, 'course_subject_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignIdFor(SemesterSection::class, 'semester_section_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->unique(['student_semester_id', 'course_subject_id']);

            $table->timestampTz('archived_at')->nullable();
            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_subjects');
        Schema::dropIfExists('student_semesters');
        Schema::dropIfExists('students');
    }
};
