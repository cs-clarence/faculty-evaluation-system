<?php

use App\Models\CourseSubject;
use App\Models\Semester;
use App\Models\SemesterSection;
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
            $table->timestampTz('archived_at')->nullable();
            $table->timestampsTz();
        });

        Schema::create('teacher_semesters', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Teacher::class, 'teacher_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignIdFor(Semester::class, 'semester_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestampTz('archived_at')->nullable();

            $table->timestampsTz();
        });

        Schema::create('teacher_subjects', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(CourseSubject::class, 'course_subject_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignIdFor(TeacherSemester::class, 'teacher_semester_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestampTz('archived_at')->nullable();

            $table->foreignIdFor(SemesterSection::class, 'semester_section_id')
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
        Schema::dropIfExists('teacher_subjects');
        Schema::dropIfExists('teacher_semesters');
        Schema::dropIfExists('teachers');
    }
};
