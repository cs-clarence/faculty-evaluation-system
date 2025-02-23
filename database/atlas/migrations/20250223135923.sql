-- Create "form_submission_departments" table
CREATE TABLE "form_submission_departments" (
  "id" bigserial NOT NULL,
  "department_id" bigint NOT NULL,
  "form_submission_id" bigint NOT NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "form_submission_departments_form_submission_id_department_id_un" UNIQUE ("form_submission_id", "department_id"),
  CONSTRAINT "form_submission_departments_form_submission_id_unique" UNIQUE ("form_submission_id"),
  CONSTRAINT "form_submission_departments_department_id_foreign" FOREIGN KEY ("department_id") REFERENCES "departments" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT "form_submission_departments_form_submission_id_foreign" FOREIGN KEY ("form_submission_id") REFERENCES "form_submissions" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);
