-- Rename a column from "name" to "label"
ALTER TABLE "form_question_options" RENAME COLUMN "name" TO "label";
-- Modify "form_question_options" table
ALTER TABLE "form_question_options" ADD COLUMN "order_numerator" integer NOT NULL, ADD COLUMN "order_denominator" integer NOT NULL DEFAULT 1, ADD CONSTRAINT "form_question_options_form_question_id_label_unique" UNIQUE ("form_question_id", "label"), ADD CONSTRAINT "form_question_options_order_numerator_order_denominator_unique" UNIQUE ("order_numerator", "order_denominator");
-- Rename a column from "question" to "title"
ALTER TABLE "form_questions" RENAME COLUMN "question" TO "title";
-- Modify "form_questions" table
ALTER TABLE "form_questions" ADD COLUMN "order_numerator" integer NOT NULL, ADD COLUMN "order_denominator" integer NOT NULL DEFAULT 1, ADD CONSTRAINT "form_questions_order_numerator_order_denominator_unique" UNIQUE ("order_numerator", "order_denominator");
-- Rename a column from "name" to "title"
ALTER TABLE "form_sections" RENAME COLUMN "name" TO "title";
-- Modify "form_sections" table
ALTER TABLE "form_sections" ADD COLUMN "order_numerator" integer NOT NULL, ADD COLUMN "order_denominator" integer NOT NULL DEFAULT 1, ADD COLUMN "archived_at" timestamptz(0) NULL, ADD CONSTRAINT "form_sections_form_id_title_unique" UNIQUE ("form_id", "title"), ADD CONSTRAINT "form_sections_order_numerator_order_denominator_unique" UNIQUE ("order_numerator", "order_denominator");
-- Modify "form_submission_answers" table
ALTER TABLE "form_submission_answers" ADD COLUMN "reason" character varying(255) NULL;
-- Create "deans" table
CREATE TABLE "deans" (
  "id" bigserial NOT NULL,
  "user_id" bigint NOT NULL,
  "department_id" bigint NOT NULL,
  "archived_at" timestamptz(0) NULL,
  "created_at" timestamptz(0) NULL,
  "updated_at" timestamptz(0) NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "deans_department_id_foreign" FOREIGN KEY ("department_id") REFERENCES "departments" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT "deans_user_id_foreign" FOREIGN KEY ("user_id") REFERENCES "users" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);
-- Create "evaluators" table
CREATE TABLE "evaluators" (
  "id" bigserial NOT NULL,
  "user_id" bigint NOT NULL,
  "archived_at" timestamptz(0) NULL,
  "created_at" timestamptz(0) NULL,
  "updated_at" timestamptz(0) NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "evaluators_user_id_foreign" FOREIGN KEY ("user_id") REFERENCES "users" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);
-- Modify "roles" table
ALTER TABLE "roles" ADD COLUMN "can_be_evaluator" boolean NOT NULL DEFAULT false, ADD COLUMN "can_be_evaluatee" boolean NOT NULL DEFAULT true;
-- Modify "form_submission_periods" table
ALTER TABLE "form_submission_periods" ALTER COLUMN "semester_id" DROP NOT NULL, ADD COLUMN "evaluator_role_id" bigint NOT NULL, ADD COLUMN "evaluatee_role_id" bigint NOT NULL, ADD CONSTRAINT "form_submission_periods_evaluatee_role_id_foreign" FOREIGN KEY ("evaluatee_role_id") REFERENCES "roles" ("id") ON UPDATE CASCADE ON DELETE CASCADE, ADD CONSTRAINT "form_submission_periods_evaluator_role_id_foreign" FOREIGN KEY ("evaluator_role_id") REFERENCES "roles" ("id") ON UPDATE CASCADE ON DELETE CASCADE;
-- Modify "form_submissions" table
ALTER TABLE "form_submissions" DROP COLUMN "student_subject_id", DROP COLUMN "form_id", DROP COLUMN "teacher_id", ADD COLUMN "evaluator_id" bigint NOT NULL, ADD COLUMN "evaluatee_id" bigint NOT NULL, ADD CONSTRAINT "form_submissions_evaluator_id_evaluatee_id_form_submission_peri" UNIQUE ("evaluator_id", "evaluatee_id", "form_submission_period_id"), ADD CONSTRAINT "form_submissions_evaluatee_id_foreign" FOREIGN KEY ("evaluatee_id") REFERENCES "users" ("id") ON UPDATE CASCADE ON DELETE CASCADE, ADD CONSTRAINT "form_submissions_evaluator_id_foreign" FOREIGN KEY ("evaluator_id") REFERENCES "users" ("id") ON UPDATE CASCADE ON DELETE CASCADE;
-- Create "human_resources_staff" table
CREATE TABLE "human_resources_staff" (
  "id" bigserial NOT NULL,
  "user_id" bigint NOT NULL,
  "archived_at" timestamptz(0) NULL,
  "created_at" timestamptz(0) NULL,
  "updated_at" timestamptz(0) NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "human_resources_staff_user_id_foreign" FOREIGN KEY ("user_id") REFERENCES "users" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);
