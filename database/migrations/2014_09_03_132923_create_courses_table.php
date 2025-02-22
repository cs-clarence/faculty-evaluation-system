<?php

use App\Models\CourseSemester;
use App\Models\SchoolYear;
use App\Models\Subject;
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
        Schema::create('school_years', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('year_start');
            $table->unsignedInteger('year_end');
            $table->timestampTz('archived_at')->nullable();

            $table->unique('year_start', 'year_end');
            $table->timestampsTz();
        });

        Schema::create('semesters', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('semester');

            $table->foreignIdFor(SchoolYear::class, 'school_year_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->unique(['school_year_id', 'semester']);
            $table->timestampTz('archived_at')->nullable();
            $table->timestampsTz();
        });

        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code');
            $table->timestampTz('archived_at')->nullable();

            $table->timestampsTz();
            $table->fullText(['name', 'code']);
        });

        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->timestampTz('archived_at')->nullable();

            $table->timestampsTz();
            $table->fullText(['name', 'code']);
        });

        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('department_id');

            $table->string('code')->unique();
            $table->string('name');
            $table->timestampTz('archived_at')->nullable();

            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade')->onUpdate('cascade');
            $table->timestampsTz();
            $table->fullText(['name', 'code']);
        });

        Schema::create('course_semesters', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('year_level');
            $table->unsignedInteger('semester');

            $table->unsignedInteger('course_id');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade')->onUpdate('cascade');
            $table->timestampTz('archived_at')->nullable();

            $table->unique(['course_id', 'year_level', 'semester']);

            $table->timestampsTz();
        });

        Schema::create('course_subjects', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(CourseSemester::class, 'course_semester_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignIdFor(Subject::class, 'subject_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->timestampTz('archived_at')->nullable();

            $table->unique(['course_semester_id', 'subject_id']);

            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_subjects');
        Schema::dropIfExists('course_semesters');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('semesters');
        Schema::dropIfExists('school_years');
    }
};
