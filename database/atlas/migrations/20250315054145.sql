-- Create "registrars" table
CREATE TABLE "registrars" (
  "id" bigserial NOT NULL,
  "user_id" bigint NOT NULL,
  "archived_at" timestamptz(0) NULL,
  "created_at" timestamptz(0) NULL,
  "updated_at" timestamptz(0) NULL,
  PRIMARY KEY ("id"),
  CONSTRAINT "registrars_user_id_foreign" FOREIGN KEY ("user_id") REFERENCES "users" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);
-- Drop "evaluators" table
DROP TABLE "evaluators";
