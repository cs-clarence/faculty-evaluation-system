-- Modify "form_submission_answers" table
ALTER TABLE "form_submission_answers" ADD COLUMN "text" character varying(255) NULL;
-- Create "form_question_essay_type_configuration" table
CREATE TABLE "form_question_essay_type_configuration" (
  "id" bigserial NOT NULL,
  "form_question_id" bigint NOT NULL,
  "value_scale_from" double precision NOT NULL,
  "value_scale_to" double precision NOT NULL,
  "archived_at" timestamptz(0) NULL,
  "created_at" timestamptz(0) NULL,
  "updated_at" timestamptz(0) NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "form_question_essay_type_configuration_form_question_id_foreign" FOREIGN KEY ("form_question_id") REFERENCES "form_questions" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);
