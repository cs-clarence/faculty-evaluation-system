-- Modify "form_submission_periods" table
ALTER TABLE "form_submission_periods" ADD CONSTRAINT "form_submission_periods_semester_id_form_id_unique" UNIQUE ("semester_id", "form_id");
-- Modify "student_subjects" table
ALTER TABLE "student_subjects" DROP COLUMN "subject_id", ADD CONSTRAINT "student_subjects_student_semester_id_course_subject_id_unique" UNIQUE ("student_semester_id", "course_subject_id");
-- Modify "teacher_subjects" table
ALTER TABLE "teacher_subjects" DROP COLUMN "subject_id", ADD CONSTRAINT "teacher_subjects_teacher_semester_id_course_subject_id_unique" UNIQUE ("teacher_semester_id", "course_subject_id");
-- Modify "form_submissions" table
ALTER TABLE "form_submissions" ADD COLUMN "teacher_id" bigint NOT NULL, ADD COLUMN "archived_at" timestamptz(0) NULL, ADD CONSTRAINT "form_submissions_student_subject_id_unique" UNIQUE ("student_subject_id"), ADD CONSTRAINT "form_submissions_teacher_id_student_subject_id_unique" UNIQUE ("teacher_id", "student_subject_id"), ADD
 CONSTRAINT "form_submissions_teacher_id_foreign" FOREIGN KEY ("teacher_id") REFERENCES "teachers" ("id") ON UPDATE CASCADE ON DELETE CASCADE;
-- Modify "students" table
ALTER TABLE "students" ALTER COLUMN "user_id" TYPE bigint, ALTER COLUMN "course_id" TYPE bigint, ADD COLUMN "starting_school_year_id" bigint NOT NULL, ADD
 CONSTRAINT "students_starting_school_year_id_foreign" FOREIGN KEY ("starting_school_year_id") REFERENCES "school_years" ("id") ON UPDATE CASCADE ON DELETE CASCADE;
