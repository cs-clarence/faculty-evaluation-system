-- Modify "users" table
ALTER TABLE "users" DROP CONSTRAINT "users_role_id_foreign", ALTER COLUMN "role_id" TYPE bigint;
-- Create "sessions" table
CREATE TABLE "sessions" (
  "id" character varying(255) NOT NULL,
  "user_id" bigint NULL,
  "ip_address" character varying(45) NULL,
  "user_agent" text NULL,
  "payload" text NOT NULL,
  "last_activity" integer NOT NULL,
  PRIMARY KEY ("id")
);
-- Create index "sessions_last_activity_index" to table: "sessions"
CREATE INDEX "sessions_last_activity_index" ON "sessions" ("last_activity");
-- Create index "sessions_user_id_index" to table: "sessions"
CREATE INDEX "sessions_user_id_index" ON "sessions" ("user_id");
