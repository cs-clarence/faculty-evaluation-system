<?php

use App\Models\Course;
use App\Models\Section;
use App\Models\Semester;
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
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->unsignedInteger('year_level');
            $table->foreignIdFor(Course::class, 'course_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->unique(['year_level', 'code', 'course_id']);

            $table->timestampTz('archived_at')->nullable();
            $table->timestampsTz();
        });

        Schema::create('semester_sections', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Section::class, 'section_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignIdFor(Semester::class, 'semester_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->unique(['section_id', 'semester_id']);

            $table->timestampTz('archived_at')->nullable();
            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('semester_sections');
        Schema::dropIfExists('sections');
    }
};
