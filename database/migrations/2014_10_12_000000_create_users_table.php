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
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('display_name');
            $table->string('code');
            $table->boolean('hidden');
            $table->boolean('can_be_evaluator')->default(false);
            $table->boolean('can_be_evaluatee')->default(true);

            $table->unique('code');
            $table->timestampsTz();
            $table->fullText(['display_name', 'code']);
        });

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');

            $table->unsignedInteger('role_id');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade')->onUpdate('cascade');

            $table->string('email')->unique();
            $table->timestampTz('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestampTz('archived_at')->nullable();
            $table->boolean('active')->default(true);
            $table->timestampsTz();
            $table->fullText(['name', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
    }
};
