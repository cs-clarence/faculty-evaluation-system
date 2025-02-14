<?php

use App\Models\Form;
use App\Models\FormQuestion;
use App\Models\FormQuestionOption;
use App\Models\FormSection;
use App\Models\FormSubmission;
use App\Models\FormSubmissionAnswer;
use App\Models\FormSubmissionPeriod;
use App\Models\Role;
use App\Models\Semester;
use App\Models\Teacher;
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
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestampTz('archived_at')->nullable();
            $table->timestampsTz();
        });

        Schema::create('form_sections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->foreignIdFor(Form::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->timestampsTz();
        });

        Schema::create('form_questions', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->string('description')->nullable();
            $table->string('type');
            $table->foreignIdFor(Form::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignIdFor(FormSection::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->timestampTz('archived_at')->nullable();
            $table->timestampsTz();
        });

        Schema::create('form_question_essay_type_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(FormQuestion::class, 'form_question_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->float('value_scale_from');
            $table->float('value_scale_to');

            $table->timestampTz('archived_at')->nullable();
            $table->timestampsTz();
        });

        Schema::create('form_question_options', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('interpretation')->nullable();
            $table->float('value');
            $table->foreignIdFor(FormQuestion::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->timestampsTz();
        });

        Schema::create('form_submission_periods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestampTz('starts_at');
            $table->timestampTz('ends_at');
            $table->boolean('is_open');
            $table->boolean('is_submissions_editable');
            $table->dateTimeTz('archived_at')->nullable();

            $table->foreignIdFor(Role::class, 'evaluator_role_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignIdFor(Role::class, 'evaluatee_role_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignIdFor(Semester::class, 'semester_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignIdFor(Form::class, 'form_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->unique(['semester_id', 'form_id']);

            $table->timestampsTz();
        });

        Schema::create('form_submissions', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(User::class, 'evaluator_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignIdFor(User::class, 'evaluatee_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignIdFor(Teacher::class, 'teacher_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignIdFor(FormSubmissionPeriod::class, 'form_submission_period_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignIdFor(Form::class, 'form_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->unique([
                'evaluator_id',
                'evaluatee_id',
                'form_submission_period_id',
            ]);

            $table->timestampTz('archived_at')->nullable();

            $table->timestampsTz();
        });

        Schema::create('form_submission_answers', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(FormSubmission::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignIdFor(FormQuestion::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->float('value');
            $table->text('text')->nullable();
            $table->string('interpretation')->nullable();
            $table->string('reason')->nullable();

            $table->timestampsTz();
        });

        Schema::create('form_submission_answer_selected_options', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(FormSubmissionAnswer::class, 'form_submission_answer_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignIdFor(FormQuestionOption::class, 'form_question_option_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_submission_answer_selected_options');
        Schema::dropIfExists('form_submission_answers');
        Schema::dropIfExists('form_submissions');
        Schema::dropIfExists('form_submission_periods');
        Schema::dropIfExists('form_question_options');
        Schema::dropIfExists('form_question_essay_type_configurations');
        Schema::dropIfExists('form_questions');
        Schema::dropIfExists('form_sections');
        Schema::dropIfExists('forms');
    }
};
