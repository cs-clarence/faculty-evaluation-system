-- Modify "users" table
ALTER TABLE "users" ADD CONSTRAINT "users_role_id_foreign" FOREIGN KEY ("role_id") REFERENCES "roles" ("id") ON UPDATE CASCADE ON DELETE CASCADE;
