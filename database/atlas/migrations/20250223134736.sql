-- Drop index "form_submission_answers_text_interpretation_reason_fulltext" from table: "form_submission_answers"
DROP INDEX "form_submission_answers_text_interpretation_reason_fulltext";
-- Modify "form_submission_answers" table
ALTER TABLE "form_submission_answers" ALTER COLUMN "text" TYPE character varying(10239), ALTER COLUMN "reason" TYPE character varying(10239);
-- Create index "form_submission_answers_text_interpretation_reason_fulltext" to table: "form_submission_answers"
CREATE INDEX "form_submission_answers_text_interpretation_reason_fulltext" ON "form_submission_answers" USING gin ((((to_tsvector('english'::regconfig, (text)::text) || to_tsvector('english'::regconfig, (interpretation)::text)) || to_tsvector('english'::regconfig, (reason)::text))));
-- Modify "form_submissions" table
ALTER TABLE "form_submissions" DROP CONSTRAINT "form_submissions_evaluator_id_evaluatee_id_form_submission_peri";
-- Create "form_submission_subjects" table
CREATE TABLE "form_submission_subjects" (
  "id" bigserial NOT NULL,
  "course_subject_id" bigint NOT NULL,
  "student_subject_id" bigint NULL,
  "form_submission_id" bigint NOT NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "form_submission_subjects_form_submission_id_course_subject_id_u" UNIQUE ("form_submission_id", "course_subject_id"),
  CONSTRAINT "form_submission_subjects_form_submission_id_unique" UNIQUE ("form_submission_id"),
  CONSTRAINT "form_submission_subjects_student_subject_id_unique" UNIQUE ("student_subject_id"),
  CONSTRAINT "form_submission_subjects_course_subject_id_foreign" FOREIGN KEY ("course_subject_id") REFERENCES "course_subjects" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT "form_submission_subjects_form_submission_id_foreign" FOREIGN KEY ("form_submission_id") REFERENCES "form_submissions" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT "form_submission_subjects_student_subject_id_foreign" FOREIGN KEY ("student_subject_id") REFERENCES "student_subjects" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);
-- Drop "form_submission_student_subject" table
DROP TABLE "form_submission_student_subject";
