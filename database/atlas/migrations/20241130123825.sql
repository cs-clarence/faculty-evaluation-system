-- Modify "students" table
ALTER TABLE "students" ALTER COLUMN "course_id" SET NOT NULL, ALTER COLUMN "student_number" SET NOT NULL, ALTER COLUMN "address" DROP NOT NULL;
-- Modify "teachers" table
ALTER TABLE "teachers" ADD COLUMN "department_id" bigint NOT NULL, ADD
 CONSTRAINT "teachers_department_id_foreign" FOREIGN KEY ("department_id") REFERENCES "departments" ("id") ON UPDATE CASCADE ON DELETE CASCADE;
