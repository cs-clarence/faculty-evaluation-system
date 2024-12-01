-- Modify "form_submissions" table
ALTER TABLE "form_submissions" DROP CONSTRAINT "form_submissions_teacher_id_student_subject_id_unique", ADD CONSTRAINT "form_submissions_teacher_id_student_subject_id_form_submission_" UNIQUE ("teacher_id", "student_subject_id", "form_submission_period_id");
