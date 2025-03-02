-- Modify "teacher_subjects" table
ALTER TABLE "teacher_subjects" ALTER COLUMN "semester_section_id" DROP NOT NULL;
-- Create "jobs" table
CREATE TABLE "jobs" (
  "id" bigserial NOT NULL,
  "queue" character varying(255) NOT NULL,
  "payload" text NOT NULL,
  "attempts" smallint NOT NULL,
  "reserved_at" integer NULL,
  "available_at" integer NOT NULL,
  "created_at" integer NOT NULL,
  PRIMARY KEY ("id")
);
-- Create index "jobs_queue_index" to table: "jobs"
CREATE INDEX "jobs_queue_index" ON "jobs" ("queue");
-- Modify "student_semesters" table
ALTER TABLE "student_semesters" ADD COLUMN "course_semester_id" bigint NOT NULL, ADD COLUMN "year_level" integer NOT NULL, ADD CONSTRAINT "student_semesters_course_semester_id_foreign" FOREIGN KEY ("course_semester_id") REFERENCES "course_semesters" ("id") ON UPDATE CASCADE ON DELETE CASCADE;
