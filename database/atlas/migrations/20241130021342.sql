-- Create "departments" table
CREATE TABLE "departments" (
  "id" bigserial NOT NULL,
  "name" character varying(255) NOT NULL,
  "code" character varying(255) NOT NULL,
  "archived_at" timestamptz(0) NULL,
  "created_at" timestamptz(0) NULL,
  "updated_at" timestamptz(0) NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "departments_name_unique" UNIQUE ("name")
);
-- Create "failed_jobs" table
CREATE TABLE "failed_jobs" (
  "id" bigserial NOT NULL,
  "uuid" character varying(255) NOT NULL,
  "connection" text NOT NULL,
  "queue" text NOT NULL,
  "payload" text NOT NULL,
  "exception" text NOT NULL,
  "failed_at" timestamptz(0) NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY ("id"),
  CONSTRAINT "failed_jobs_uuid_unique" UNIQUE ("uuid")
);
-- Create "personal_access_tokens" table
CREATE TABLE "personal_access_tokens" (
  "id" bigserial NOT NULL,
  "tokenable_type" character varying(255) NOT NULL,
  "tokenable_id" bigint NOT NULL,
  "name" character varying(255) NOT NULL,
  "token" character varying(64) NOT NULL,
  "abilities" text NULL,
  "last_used_at" timestamptz(0) NULL,
  "expires_at" timestamptz(0) NULL,
  "created_at" timestamptz(0) NULL,
  "updated_at" timestamptz(0) NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "personal_access_tokens_token_unique" UNIQUE ("token")
);
-- Create index "personal_access_tokens_tokenable_type_tokenable_id_index" to table: "personal_access_tokens"
CREATE INDEX "personal_access_tokens_tokenable_type_tokenable_id_index" ON "personal_access_tokens" ("tokenable_type", "tokenable_id");
-- Create "password_reset_tokens" table
CREATE TABLE "password_reset_tokens" (
  "email" character varying(255) NOT NULL,
  "token" character varying(255) NOT NULL,
  "created_at" timestamptz(0) NULL,
  PRIMARY KEY ("email")
);
-- Create "migrations" table
CREATE TABLE "migrations" (
  "id" serial NOT NULL,
  "migration" character varying(255) NOT NULL,
  "batch" integer NOT NULL,
  PRIMARY KEY ("id")
);
-- Create "courses" table
CREATE TABLE "courses" (
  "id" bigserial NOT NULL,
  "department_id" integer NOT NULL,
  "code" character varying(255) NOT NULL,
  "name" character varying(255) NOT NULL,
  "archived_at" timestamptz(0) NULL,
  "created_at" timestamptz(0) NULL,
  "updated_at" timestamptz(0) NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "courses_code_unique" UNIQUE ("code"),
  CONSTRAINT "courses_department_id_foreign" FOREIGN KEY ("department_id") REFERENCES "departments" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);
-- Create "course_semesters" table
CREATE TABLE "course_semesters" (
  "id" bigserial NOT NULL,
  "year_level" integer NOT NULL,
  "semester" integer NOT NULL,
  "course_id" integer NOT NULL,
  "archived_at" timestamptz(0) NULL,
  "created_at" timestamptz(0) NULL,
  "updated_at" timestamptz(0) NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "course_semesters_course_id_year_level_semester_unique" UNIQUE ("course_id", "year_level", "semester"),
  CONSTRAINT "course_semesters_course_id_foreign" FOREIGN KEY ("course_id") REFERENCES "courses" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);
-- Create "subjects" table
CREATE TABLE "subjects" (
  "id" bigserial NOT NULL,
  "code" character varying(255) NOT NULL,
  "name" character varying(255) NOT NULL,
  "archived_at" timestamptz(0) NULL,
  "created_at" timestamptz(0) NULL,
  "updated_at" timestamptz(0) NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "subjects_code_unique" UNIQUE ("code")
);
-- Create "course_subjects" table
CREATE TABLE "course_subjects" (
  "id" bigserial NOT NULL,
  "course_semester_id" bigint NOT NULL,
  "subject_id" bigint NOT NULL,
  "archived_at" timestamptz(0) NULL,
  "created_at" timestamptz(0) NULL,
  "updated_at" timestamptz(0) NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "course_subjects_course_semester_id_subject_id_unique" UNIQUE ("course_semester_id", "subject_id"),
  CONSTRAINT "course_subjects_course_semester_id_foreign" FOREIGN KEY ("course_semester_id") REFERENCES "course_semesters" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT "course_subjects_subject_id_foreign" FOREIGN KEY ("subject_id") REFERENCES "subjects" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);
-- Create "forms" table
CREATE TABLE "forms" (
  "id" bigserial NOT NULL,
  "name" character varying(255) NOT NULL,
  "description" character varying(255) NULL,
  "archived_at" timestamptz(0) NULL,
  "created_at" timestamptz(0) NULL,
  "updated_at" timestamptz(0) NULL,
  PRIMARY KEY ("id")
);
-- Create "form_sections" table
CREATE TABLE "form_sections" (
  "id" bigserial NOT NULL,
  "name" character varying(255) NOT NULL,
  "description" character varying(255) NULL,
  "form_id" bigint NOT NULL,
  "created_at" timestamptz(0) NULL,
  "updated_at" timestamptz(0) NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "form_sections_form_id_foreign" FOREIGN KEY ("form_id") REFERENCES "forms" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);
-- Create "form_questions" table
CREATE TABLE "form_questions" (
  "id" bigserial NOT NULL,
  "question" character varying(255) NOT NULL,
  "description" character varying(255) NULL,
  "type" character varying(255) NOT NULL,
  "form_id" bigint NOT NULL,
  "form_section_id" bigint NOT NULL,
  "archived_at" timestamptz(0) NULL,
  "created_at" timestamptz(0) NULL,
  "updated_at" timestamptz(0) NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "form_questions_form_id_foreign" FOREIGN KEY ("form_id") REFERENCES "forms" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT "form_questions_form_section_id_foreign" FOREIGN KEY ("form_section_id") REFERENCES "form_sections" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);
-- Create "form_question_options" table
CREATE TABLE "form_question_options" (
  "id" bigserial NOT NULL,
  "name" character varying(255) NOT NULL,
  "interpretation" character varying(255) NULL,
  "value" double precision NOT NULL,
  "form_question_id" bigint NOT NULL,
  "created_at" timestamptz(0) NULL,
  "updated_at" timestamptz(0) NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "form_question_options_form_question_id_foreign" FOREIGN KEY ("form_question_id") REFERENCES "form_questions" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);
-- Create "school_years" table
CREATE TABLE "school_years" (
  "id" bigserial NOT NULL,
  "year_start" integer NOT NULL,
  "year_end" integer NOT NULL,
  "archived_at" timestamptz(0) NULL,
  "created_at" timestamptz(0) NULL,
  "updated_at" timestamptz(0) NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "year_end" UNIQUE ("year_start")
);
-- Create "semesters" table
CREATE TABLE "semesters" (
  "id" bigserial NOT NULL,
  "semester" integer NOT NULL,
  "school_year_id" bigint NOT NULL,
  "archived_at" timestamptz(0) NULL,
  "created_at" timestamptz(0) NULL,
  "updated_at" timestamptz(0) NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "semesters_school_year_id_semester_unique" UNIQUE ("school_year_id", "semester"),
  CONSTRAINT "semesters_school_year_id_foreign" FOREIGN KEY ("school_year_id") REFERENCES "school_years" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);
-- Create "form_submission_periods" table
CREATE TABLE "form_submission_periods" (
  "id" bigserial NOT NULL,
  "name" character varying(255) NOT NULL,
  "starts_at" timestamptz(0) NOT NULL,
  "ends_at" timestamptz(0) NOT NULL,
  "is_open" boolean NOT NULL,
  "is_submissions_editable" boolean NOT NULL,
  "archived_at" timestamptz(0) NULL,
  "semester_id" bigint NOT NULL,
  "form_id" bigint NOT NULL,
  "created_at" timestamptz(0) NULL,
  "updated_at" timestamptz(0) NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "form_submission_periods_form_id_foreign" FOREIGN KEY ("form_id") REFERENCES "forms" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT "form_submission_periods_semester_id_foreign" FOREIGN KEY ("semester_id") REFERENCES "semesters" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);
-- Create "sections" table
CREATE TABLE "sections" (
  "id" bigserial NOT NULL,
  "name" character varying(255) NOT NULL,
  "code" character varying(255) NOT NULL,
  "year_level" integer NOT NULL,
  "semester" integer NOT NULL,
  "course_id" bigint NOT NULL,
  "archived_at" timestamptz(0) NULL,
  "created_at" timestamptz(0) NULL,
  "updated_at" timestamptz(0) NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "sections_code_unique" UNIQUE ("code"),
  CONSTRAINT "sections_year_level_semester_course_id_name_unique" UNIQUE ("year_level", "semester", "course_id", "name"),
  CONSTRAINT "sections_course_id_foreign" FOREIGN KEY ("course_id") REFERENCES "courses" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);
-- Create "semester_sections" table
CREATE TABLE "semester_sections" (
  "id" bigserial NOT NULL,
  "section_id" bigint NOT NULL,
  "semester_id" bigint NOT NULL,
  "archived_at" timestamptz(0) NULL,
  "created_at" timestamptz(0) NULL,
  "updated_at" timestamptz(0) NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "semester_sections_section_id_semester_id_unique" UNIQUE ("section_id", "semester_id"),
  CONSTRAINT "semester_sections_section_id_foreign" FOREIGN KEY ("section_id") REFERENCES "sections" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT "semester_sections_semester_id_foreign" FOREIGN KEY ("semester_id") REFERENCES "semesters" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);
-- Create "roles" table
CREATE TABLE "roles" (
  "id" serial NOT NULL,
  "display_name" character varying(255) NOT NULL,
  "code" character varying(255) NOT NULL,
  "hidden" boolean NOT NULL,
  "created_at" timestamptz(0) NULL,
  "updated_at" timestamptz(0) NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "roles_code_unique" UNIQUE ("code")
);
-- Create "users" table
CREATE TABLE "users" (
  "id" serial NOT NULL,
  "name" character varying(255) NOT NULL,
  "role_id" integer NOT NULL,
  "email" character varying(255) NOT NULL,
  "email_verified_at" timestamptz(0) NULL,
  "password" character varying(255) NOT NULL,
  "remember_token" character varying(100) NULL,
  "archived_at" timestamptz(0) NULL,
  "created_at" timestamptz(0) NULL,
  "updated_at" timestamptz(0) NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "users_email_unique" UNIQUE ("email"),
  CONSTRAINT "users_role_id_foreign" FOREIGN KEY ("role_id") REFERENCES "roles" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);
-- Create "students" table
CREATE TABLE "students" (
  "id" bigserial NOT NULL,
  "user_id" integer NOT NULL,
  "course_id" integer NULL,
  "student_number" character varying(255) NULL,
  "address" character varying(255) NOT NULL,
  "created_at" timestamptz(0) NULL,
  "updated_at" timestamptz(0) NULL,
  "archived_at" timestamptz(0) NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "students_student_number_unique" UNIQUE ("student_number"),
  CONSTRAINT "students_course_id_foreign" FOREIGN KEY ("course_id") REFERENCES "courses" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT "students_user_id_foreign" FOREIGN KEY ("user_id") REFERENCES "users" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);
-- Create "student_semesters" table
CREATE TABLE "student_semesters" (
  "id" bigserial NOT NULL,
  "student_id" bigint NOT NULL,
  "semester_id" bigint NOT NULL,
  "archived_at" timestamptz(0) NULL,
  "created_at" timestamptz(0) NULL,
  "updated_at" timestamptz(0) NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "student_semesters_student_id_semester_id_unique" UNIQUE ("student_id", "semester_id"),
  CONSTRAINT "student_semesters_semester_id_foreign" FOREIGN KEY ("semester_id") REFERENCES "semesters" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT "student_semesters_student_id_foreign" FOREIGN KEY ("student_id") REFERENCES "students" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);
-- Create "student_subjects" table
CREATE TABLE "student_subjects" (
  "id" bigserial NOT NULL,
  "subject_id" bigint NOT NULL,
  "student_semester_id" bigint NOT NULL,
  "course_subject_id" bigint NOT NULL,
  "semester_section_id" bigint NOT NULL,
  "archived_at" timestamptz(0) NULL,
  "created_at" timestamptz(0) NULL,
  "updated_at" timestamptz(0) NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "student_subjects_student_semester_id_subject_id_unique" UNIQUE ("student_semester_id", "subject_id"),
  CONSTRAINT "student_subjects_course_subject_id_foreign" FOREIGN KEY ("course_subject_id") REFERENCES "course_subjects" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT "student_subjects_semester_section_id_foreign" FOREIGN KEY ("semester_section_id") REFERENCES "semester_sections" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT "student_subjects_student_semester_id_foreign" FOREIGN KEY ("student_semester_id") REFERENCES "student_semesters" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT "student_subjects_subject_id_foreign" FOREIGN KEY ("subject_id") REFERENCES "subjects" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);
-- Create "form_submissions" table
CREATE TABLE "form_submissions" (
  "id" bigserial NOT NULL,
  "student_subject_id" bigint NOT NULL,
  "form_submission_period_id" bigint NOT NULL,
  "form_id" bigint NOT NULL,
  "created_at" timestamptz(0) NULL,
  "updated_at" timestamptz(0) NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "form_submissions_form_id_foreign" FOREIGN KEY ("form_id") REFERENCES "forms" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT "form_submissions_form_submission_period_id_foreign" FOREIGN KEY ("form_submission_period_id") REFERENCES "form_submission_periods" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT "form_submissions_student_subject_id_foreign" FOREIGN KEY ("student_subject_id") REFERENCES "student_subjects" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);
-- Create "form_submission_answers" table
CREATE TABLE "form_submission_answers" (
  "id" bigserial NOT NULL,
  "form_submission_id" bigint NOT NULL,
  "form_question_id" bigint NOT NULL,
  "value" double precision NOT NULL,
  "interpretation" character varying(255) NULL,
  "created_at" timestamptz(0) NULL,
  "updated_at" timestamptz(0) NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "form_submission_answers_form_question_id_foreign" FOREIGN KEY ("form_question_id") REFERENCES "form_questions" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT "form_submission_answers_form_submission_id_foreign" FOREIGN KEY ("form_submission_id") REFERENCES "form_submissions" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);
-- Create "form_submission_answer_selected_options" table
CREATE TABLE "form_submission_answer_selected_options" (
  "id" bigserial NOT NULL,
  "form_submission_answer_id" bigint NOT NULL,
  "form_question_option_id" bigint NOT NULL,
  "created_at" timestamptz(0) NULL,
  "updated_at" timestamptz(0) NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "form_submission_answer_selected_options_form_question_option_id" FOREIGN KEY ("form_question_option_id") REFERENCES "form_question_options" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT "form_submission_answer_selected_options_form_submission_answer_" FOREIGN KEY ("form_submission_answer_id") REFERENCES "form_submission_answers" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);
-- Create "teachers" table
CREATE TABLE "teachers" (
  "id" bigserial NOT NULL,
  "user_id" bigint NOT NULL,
  "archived_at" timestamptz(0) NULL,
  "created_at" timestamptz(0) NULL,
  "updated_at" timestamptz(0) NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "teachers_user_id_foreign" FOREIGN KEY ("user_id") REFERENCES "users" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);
-- Create "teacher_semesters" table
CREATE TABLE "teacher_semesters" (
  "id" bigserial NOT NULL,
  "teacher_id" bigint NOT NULL,
  "semester_id" bigint NOT NULL,
  "archived_at" timestamptz(0) NULL,
  "created_at" timestamptz(0) NULL,
  "updated_at" timestamptz(0) NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "teacher_semesters_semester_id_foreign" FOREIGN KEY ("semester_id") REFERENCES "semesters" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT "teacher_semesters_teacher_id_foreign" FOREIGN KEY ("teacher_id") REFERENCES "teachers" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);
-- Create "teacher_subjects" table
CREATE TABLE "teacher_subjects" (
  "id" bigserial NOT NULL,
  "subject_id" bigint NOT NULL,
  "teacher_semester_id" bigint NOT NULL,
  "course_subject_id" bigint NOT NULL,
  "semester_section_id" bigint NOT NULL,
  "archived_at" timestamptz(0) NULL,
  "created_at" timestamptz(0) NULL,
  "updated_at" timestamptz(0) NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "teacher_subjects_teacher_semester_id_subject_id_unique" UNIQUE ("teacher_semester_id", "subject_id"),
  CONSTRAINT "teacher_subjects_course_subject_id_foreign" FOREIGN KEY ("course_subject_id") REFERENCES "course_subjects" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT "teacher_subjects_semester_section_id_foreign" FOREIGN KEY ("semester_section_id") REFERENCES "sections" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT "teacher_subjects_subject_id_foreign" FOREIGN KEY ("subject_id") REFERENCES "subjects" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT "teacher_subjects_teacher_semester_id_foreign" FOREIGN KEY ("teacher_semester_id") REFERENCES "teacher_semesters" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);
