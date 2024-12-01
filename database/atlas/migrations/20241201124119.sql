-- Modify "form_submission_answers" table
ALTER TABLE "form_submission_answers" ALTER COLUMN "text" TYPE text;
-- Modify "form_question_essay_type_configurations" table
ALTER TABLE "form_question_essay_type_configurations" DROP CONSTRAINT "form_question_essay_type_configuration_form_question_id_foreign", ADD
 CONSTRAINT "form_question_essay_type_configurations_form_question_id_foreig" FOREIGN KEY ("form_question_id") REFERENCES "form_questions" ("id") ON UPDATE CASCADE ON DELETE CASCADE;
