<?php

use App\Models\CourseSemesterSubject;
use App\Models\SchoolYearSemester;
use App\Models\SchoolYearSemesterSection;
use App\Models\Student;
use App\Models\StudentSemester;
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
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('course_id')->nullable();

            $table->string('student_number')->unique()->nullable();
            $table->string('address');
            // Add other student-specific fields here
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('student_semesters', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Student::class, 'student_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignIdFor(SchoolYearSemester::class, 'school_year_semester_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->timestamps();
        });

        Schema::create('student_semester_subjects', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(CourseSemesterSubject::class, 'course_semester_subject_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignIdFor(StudentSemester::class, 'student_semester_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignIdFor(SchoolYearSemesterSection::class, 'school_year_semester_section_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
