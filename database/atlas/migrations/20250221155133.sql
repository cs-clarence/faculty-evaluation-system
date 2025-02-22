-- Create index "courses_name_code_fulltext" to table: "courses"
CREATE INDEX "courses_name_code_fulltext" ON "courses" USING gin (((to_tsvector('english'::regconfig, (name)::text) || to_tsvector('english'::regconfig, (code)::text))));
-- Create index "departments_name_code_fulltext" to table: "departments"
CREATE INDEX "departments_name_code_fulltext" ON "departments" USING gin (((to_tsvector('english'::regconfig, (name)::text) || to_tsvector('english'::regconfig, (code)::text))));
-- Create index "form_question_options_label_interpretation_fulltext" to table: "form_question_options"
CREATE INDEX "form_question_options_label_interpretation_fulltext" ON "form_question_options" USING gin (((to_tsvector('english'::regconfig, (label)::text) || to_tsvector('english'::regconfig, (interpretation)::text))));
-- Create index "form_questions_title_description_fulltext" to table: "form_questions"
CREATE INDEX "form_questions_title_description_fulltext" ON "form_questions" USING gin (((to_tsvector('english'::regconfig, (title)::text) || to_tsvector('english'::regconfig, (description)::text))));
-- Create index "form_sections_title_description_fulltext" to table: "form_sections"
CREATE INDEX "form_sections_title_description_fulltext" ON "form_sections" USING gin (((to_tsvector('english'::regconfig, (title)::text) || to_tsvector('english'::regconfig, (description)::text))));
-- Modify "form_submission_answers" table
ALTER TABLE "form_submission_answers" ALTER COLUMN "interpretation" TYPE character varying(10239), ALTER COLUMN "reason" TYPE character varying(255);
-- Create index "form_submission_answers_text_interpretation_reason_fulltext" to table: "form_submission_answers"
CREATE INDEX "form_submission_answers_text_interpretation_reason_fulltext" ON "form_submission_answers" USING gin ((((to_tsvector('english'::regconfig, text) || to_tsvector('english'::regconfig, (interpretation)::text)) || to_tsvector('english'::regconfig, (reason)::text))));
-- Modify "form_submission_period_semesters" table
ALTER TABLE "form_submission_period_semesters" ADD CONSTRAINT "semester_id" UNIQUE ("form_submission_period_id");
-- Create index "forms_name_description_fulltext" to table: "forms"
CREATE INDEX "forms_name_description_fulltext" ON "forms" USING gin (((to_tsvector('english'::regconfig, (name)::text) || to_tsvector('english'::regconfig, (description)::text))));
-- Create index "roles_display_name_code_fulltext" to table: "roles"
CREATE INDEX "roles_display_name_code_fulltext" ON "roles" USING gin (((to_tsvector('english'::regconfig, (display_name)::text) || to_tsvector('english'::regconfig, (code)::text))));
-- Create index "sections_name_code_fulltext" to table: "sections"
CREATE INDEX "sections_name_code_fulltext" ON "sections" USING gin (((to_tsvector('english'::regconfig, (name)::text) || to_tsvector('english'::regconfig, (code)::text))));
-- Create index "subjects_name_code_fulltext" to table: "subjects"
CREATE INDEX "subjects_name_code_fulltext" ON "subjects" USING gin (((to_tsvector('english'::regconfig, (name)::text) || to_tsvector('english'::regconfig, (code)::text))));
-- Modify "users" table
ALTER TABLE "users" ADD COLUMN "active" boolean NOT NULL DEFAULT true;
-- Create index "users_name_email_fulltext" to table: "users"
CREATE INDEX "users_name_email_fulltext" ON "users" USING gin (((to_tsvector('english'::regconfig, (name)::text) || to_tsvector('english'::regconfig, (email)::text))));
