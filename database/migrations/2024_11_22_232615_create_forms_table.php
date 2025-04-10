<?php

use App\Models\CourseSubject;
use App\Models\Department;
use App\Models\Form;
use App\Models\FormQuestion;
use App\Models\FormQuestionOption;
use App\Models\FormSection;
use App\Models\FormSubmission;
use App\Models\FormSubmissionAnswer;
use App\Models\FormSubmissionPeriod;
use App\Models\Role;
use App\Models\Semester;
use App\Models\StudentSubject;
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

            $table->fullText(['name', 'description']);
        });

        Schema::create('form_sections', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description')->nullable();
            $table->foreignIdFor(Form::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->unsignedInteger('order_numerator');
            $table->unsignedInteger('order_denominator')->default(1);
            $table->unique(['order_numerator', 'order_denominator']);
            $table->unique(['form_id', 'title']);
            $table->timestampTz('archived_at')->nullable();
            $table->timestampsTz();
            $table->fullText(['title', 'description']);
        });

        Schema::create('form_questions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('type');
            $table->float('weight')->default(1);
            $table->foreignIdFor(Form::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignIdFor(FormSection::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->unsignedInteger('order_numerator');
            $table->unsignedInteger('order_denominator')->default(1);
            $table->unique(['order_numerator', 'order_denominator']);
            $table->unique(['title', 'form_section_id']);
            $table->timestampTz('archived_at')->nullable();
            $table->timestampsTz();
            $table->fullText(['title', 'description']);
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
            $table->string('label');
            $table->string('interpretation')->nullable();
            $table->float('value');
            $table->foreignIdFor(FormQuestion::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->unsignedInteger('order_numerator');
            $table->unsignedInteger('order_denominator')->default(1);
            $table->unique(['order_numerator', 'order_denominator']);
            $table->unique(['form_question_id', 'label']);
            $table->timestampsTz();
            $table->fullText(['label', 'interpretation']);
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

            $table->foreignIdFor(Form::class, 'form_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->timestampsTz();
        });

        Schema::create('form_submission_period_semesters', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(FormSubmissionPeriod::class, 'form_submission_period_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignIdFor(Semester::class, 'semester_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->unique('form_submission_period_id');
            $table->unique('form_submission_period_id', 'semester_id');
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

            $table->foreignIdFor(FormSubmissionPeriod::class, 'form_submission_period_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->timestampTz('archived_at')->nullable();

            $table->timestampsTz();
        });

        Schema::create('form_submission_subjects', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(CourseSubject::class, 'course_subject_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignIdFor(StudentSubject::class, 'student_subject_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignIdFor(FormSubmission::class, 'form_submission_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->unique('form_submission_id');
            $table->unique(['form_submission_id', 'course_subject_id']);
            $table->unique('student_subject_id');
        });

        Schema::create('form_submission_departments', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Department::class, 'department_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignIdFor(FormSubmission::class, 'form_submission_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->unique('form_submission_id');
            $table->unique(['form_submission_id', 'department_id']);
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
            $table->string('text', 10239)->nullable();
            $table->string('interpretation', 10239)->nullable();
            $table->string('reason', 10239)->nullable();

            $table->timestampsTz();

            $table->fullText(['text', 'interpretation', 'reason']);
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

        DB::unprepared(<<<SQL
            CREATE OR REPLACE FUNCTION set_order_numerator()
            RETURNS TRIGGER AS $$
            DECLARE
                current_max INTEGER;
            BEGIN
              IF NEW.order_numerator = 0 THEN
                  -- Use dynamic SQL to query the maximum order_numerator value from the table
                  EXECUTE format('SELECT COALESCE(MAX(order_numerator), 0) FROM %I', TG_TABLE_NAME)
                    INTO current_max;

                  NEW.order_numerator := current_max + 1;
              END IF;
              RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        SQL);

        DB::unprepared(<<<SQL
            CREATE OR REPLACE TRIGGER set_order_numerator_form_sections
            BEFORE INSERT ON form_sections
            FOR EACH ROW
            EXECUTE FUNCTION set_order_numerator();
        SQL);

        DB::unprepared(<<<SQL
            CREATE TRIGGER set_order_numerator_form_questions
            BEFORE INSERT ON form_questions
            FOR EACH ROW
            EXECUTE FUNCTION set_order_numerator();
        SQL);

        DB::unprepared(<<<SQL
            CREATE OR REPLACE TRIGGER set_order_numerator_form_question_options
            BEFORE INSERT ON form_question_options
            FOR EACH ROW
            EXECUTE FUNCTION set_order_numerator();
        SQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS set_order_numerator_form_question_options ON form_question_options;');
        DB::unprepared('DROP TRIGGER IF EXISTS set_order_numerator_form_questions ON form_questions;');
        DB::unprepared('DROP TRIGGER IF EXISTS set_order_numerator_form_sections ON form_sections;');
        DB::unprepared('DROP FUNCTION IF EXISTS set_order_numerator;');
        Schema::dropIfExists('form_submission_answer_selected_options');
        Schema::dropIfExists('form_submission_answers');
        Schema::dropIfExists('form_submission_departments');
        Schema::dropIfExists('form_submission_subjects');
        Schema::dropIfExists('form_submissions');
        Schema::dropIfExists('form_submission_period_semesters');
        Schema::dropIfExists('form_submission_periods');
        Schema::dropIfExists('form_question_options');
        Schema::dropIfExists('form_question_essay_type_configurations');
        Schema::dropIfExists('form_questions');
        Schema::dropIfExists('form_sections');
        Schema::dropIfExists('forms');
    }
};
