<?php

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
        Schema::create('departments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('department_name')->unique();
            $table->string('department_code');
            $table->timestamps();
        });

        Schema::create('subjects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject_code')->unique();
            $table->string('subject_name');
            $table->timestamps();
        });

        Schema::create('courses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('department_id');

            $table->string('course_code');
            $table->string('course_name');

            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });

        Schema::create('course_semester', function (Blueprint $table) {
            $table->increments('id');
            $table->string('year_level');
            $table->string('semester');

            $table->unsignedInteger('course_id');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
