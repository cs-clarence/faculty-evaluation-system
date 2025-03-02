-- Modify "teacher_semesters" table
ALTER TABLE "teacher_semesters" ADD CONSTRAINT "teacher_semesters_teacher_id_semester_id_unique" UNIQUE ("teacher_id", "semester_id");
