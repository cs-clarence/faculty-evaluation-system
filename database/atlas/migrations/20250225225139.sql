-- Modify "users" table
ALTER TABLE "users" ADD COLUMN "require_change_password" boolean NOT NULL DEFAULT false;
