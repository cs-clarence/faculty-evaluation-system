-- Modify "student_semesters" table
ALTER TABLE "student_semesters" DROP CONSTRAINT "student_semesters_student_id_semester_id_unique", ALTER COLUMN "course_semester_id" DROP NOT NULL, ADD CONSTRAINT "student_semesters_student_id_semester_id_year_level_unique" UNIQUE ("student_id", "semester_id", "year_level");
