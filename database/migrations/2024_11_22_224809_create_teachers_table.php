<?php

use App\Models\CourseSemesterSubject;
use App\Models\SchoolYearSemester;
use App\Models\SchoolYearSemesterSection;
use App\Models\Teacher;
use App\Models\TeacherSemester;
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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('teacher_semesters', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Teacher::class, 'teacher_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignIdFor(SchoolYearSemester::class, 'school_year_semester_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->timestamps();
        });

        Schema::create('teacher_semester_subjects', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(CourseSemesterSubject::class, 'course_semester_subject_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignIdFor(TeacherSemester::class, 'teacher_semester_id')
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
        Schema::dropIfExists('teacher_semester_subjects');
        Schema::dropIfExists('teacher_semesters');
        Schema::dropIfExists('teachers');
    }
};
