-- Modify "student_semesters" table
ALTER TABLE "student_semesters" ADD COLUMN "semester_section_id" bigint NULL, ADD
 CONSTRAINT "student_semesters_semester_section_id_foreign" FOREIGN KEY ("semester_section_id") REFERENCES "semester_sections" ("id") ON UPDATE CASCADE ON DELETE CASCADE;
