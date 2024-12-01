<?php

use App\Models\CourseSubject;
use App\Models\Department;
use App\Models\Section;
use App\Models\Semester;
use App\Models\Teacher;
use App\Models\TeacherSemester;
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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'user_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignIdFor(Department::class, 'department_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

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

            $table->foreignIdFor(TeacherSemester::class, 'teacher_semester_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignIdFor(CourseSubject::class, 'course_subject_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignIdFor(Section::class, 'semester_section_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->unique(['teacher_semester_id', 'course_subject_id']);

            $table->timestampTz('archived_at')->nullable();
            $table->timestampsTz();
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
