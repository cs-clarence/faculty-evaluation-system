-- Modify "student_semesters" table
ALTER TABLE "student_semesters" ALTER COLUMN "semester_section_id" DROP NOT NULL, ALTER COLUMN "course_semester_id" SET NOT NULL;
