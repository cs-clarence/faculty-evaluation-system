-- Modify "form_questions" table
ALTER TABLE "form_questions" ADD COLUMN "weight" double precision NOT NULL DEFAULT 1, ADD CONSTRAINT "form_questions_title_form_section_id_unique" UNIQUE ("title", "form_section_id");
-- Modify "form_submission_answers" table
ALTER TABLE "form_submission_answers" ALTER COLUMN "reason" TYPE character varying(10239);
-- Modify "form_submission_periods" table
ALTER TABLE "form_submission_periods" DROP COLUMN "semester_id";
-- Create "form_submission_period_semesters" table
CREATE TABLE "form_submission_period_semesters" (
  "id" bigserial NOT NULL,
  "form_submission_period_id" bigint NOT NULL,
  "semester_id" bigint NOT NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "form_submission_period_semesters_form_submission_period_id_uniq" UNIQUE ("form_submission_period_id"),
  CONSTRAINT "semester_id" UNIQUE ("form_submission_period_id"),
  CONSTRAINT "form_submission_period_semesters_form_submission_period_id_fore" FOREIGN KEY ("form_submission_period_id") REFERENCES "form_submission_periods" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT "form_submission_period_semesters_semester_id_foreign" FOREIGN KEY ("semester_id") REFERENCES "semesters" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);
-- Create "form_submission_student_subject" table
CREATE TABLE "form_submission_student_subject" (
  "id" bigserial NOT NULL,
  "student_subject_id" bigint NOT NULL,
  "form_submission_id" bigint NOT NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "form_submission_student_subject_form_submission_id_unique" UNIQUE ("form_submission_id"),
  CONSTRAINT "form_submission_student_subject_form_submission_id_foreign" FOREIGN KEY ("form_submission_id") REFERENCES "form_submissions" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT "form_submission_student_subject_student_subject_id_foreign" FOREIGN KEY ("student_subject_id") REFERENCES "student_subjects" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);
