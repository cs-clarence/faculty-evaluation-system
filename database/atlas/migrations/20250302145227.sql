-- Modify "teacher_subjects" table
ALTER TABLE "teacher_subjects" DROP COLUMN "semester_section_id";
-- Create "teacher_subject_semester_sections" table
CREATE TABLE "teacher_subject_semester_sections" (
  "id" bigserial NOT NULL,
  "teacher_subject_id" bigint NOT NULL,
  "semester_section_id" bigint NOT NULL,
  "archived_at" timestamptz(0) NULL,
  "created_at" timestamptz(0) NULL,
  "updated_at" timestamptz(0) NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "teacher_subject_semester_sections_teacher_subject_id_semester_s" UNIQUE ("teacher_subject_id", "semester_section_id"),
  CONSTRAINT "teacher_subject_semester_sections_semester_section_id_foreign" FOREIGN KEY ("semester_section_id") REFERENCES "semester_sections" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT "teacher_subject_semester_sections_teacher_subject_id_foreign" FOREIGN KEY ("teacher_subject_id") REFERENCES "teacher_subjects" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);
